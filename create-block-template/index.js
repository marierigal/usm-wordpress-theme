/* eslint-disable */
const { join } = require('path');

module.exports = {
  blockTemplatesPath: join(__dirname, 'block-templates'),
  defaultValues: {
    namespace: 'usm',
    version: '0.1.0',
    dashicon: 'smiley',
    attributes: {},
    supports: {},
    render: 'file:./render.php',
  },
};
