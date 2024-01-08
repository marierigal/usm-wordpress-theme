const path = require('path');

const wpConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const { sync: glob } = require('fast-glob');
const FileManagerPlugin = require('filemanager-webpack-plugin');

const acfPhpEntries = [];

function getEntriesFromPath(fromPath, extension, pattern = '*') {
  return glob(`./${fromPath}/**/${pattern}.${extension}`).reduce((entries, entry) => {
    const relativePath = path.relative(__dirname, entry).replace(`${fromPath}/`, '');
    const outputDir = path.dirname(relativePath);
    const key = path.join(outputDir, path.basename(relativePath, `.${extension}`));
    entries[key] = entry;
    return entries;
  }, {});
}

function getAcfBlockEntries(fromPath) {
  acfPhpEntries.length = 0;

  return glob(`./${fromPath}/**/block.json`).reduce((entries, entry) => {
    const block = require(entry);

    // if block.json does not have an acf key, then skip it
    if (!block.acf) {
      return entries;
    }

    const outputDir = path.dirname(entry);
    const outputDirKey = outputDir.replace(`${fromPath}/`, '');

    // else return the filepath of acf.renderTemplate property
    if (block.acf['renderTemplate']) {
      const templateFilename = block.acf['renderTemplate'];
      const blockTemplate = path.join(outputDir, templateFilename);
      acfPhpEntries.push(blockTemplate);
    }

    // and the filepath of style property
    if (block.style) {
      const styleFilename = 'style.scss';
      const blockStyle = './' + path.join(outputDir, styleFilename);
      const key = path.join(outputDirKey, 'index');
      entries[key] = blockStyle;
    }

    return entries;
  }, {});
}

/**
 * Override the default WordPress config
 */
const defaultConfig = {
  ...wpConfig,
  entry: {
    // Default WordPress entry points
    ...getWebpackEntryPoints(),
    // Assets stylesheets entry points
    ...getEntriesFromPath('assets', 'scss'),
    // ACF blocks stylesheets entry points
    ...getAcfBlockEntries('src'),
    // Format types entry points
    ...getEntriesFromPath('src', 'ts', 'format-types/**/index'),
  },
  plugins: [
    // Default plugins
    ...wpConfig.plugins,
    // Clean build directory and move assets stylesheets
    new FileManagerPlugin({
      events: {
        onStart: {
          delete: ['build', 'assets/css'],
        },
        onEnd: {
          copy: [{ source: 'build/scss/**/*.css', destination: 'assets/css' }],
          delete: ['build/scss'],
        },
      },
    }),
    // Copy PHP files from ACF blocks
    new CopyWebpackPlugin({
      patterns: [
        {
          from: 'blocks/**/*.php',
          context: 'src',
          noErrorOnMissing: true,
          filter: entry => acfPhpEntries.includes(path.relative(__dirname, entry)),
        },
      ],
    }),
  ],
};

module.exports = defaultConfig;
