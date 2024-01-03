import { PanelBody, SelectControl, TextControl, ToggleControl } from '@wordpress/components';
import { _x } from '@wordpress/i18n';
import { FC } from 'react';

export interface PrefixSuffixPanelProps {
  setAttributes: (props: any) => void;
  labelAsPrefix?: boolean;
  prefix?: string;
  suffix?: string;
  fieldType?: string;
  displayLayout?: string;
}

export const PrefixSuffixPanel: FC<PrefixSuffixPanelProps> = ({
  setAttributes,
  labelAsPrefix,
  prefix = '',
  suffix = '',
  fieldType,
  displayLayout,
}) => {
  const displayLayoutOptions = [
    { value: 'inline-block', label: _x('Inline block', 'Display layout option', 'usm') },
    { value: 'block', label: _x('Block', 'Display layout option', 'usm') },
    { value: '', label: _x('Auto', 'Display layout option', 'usm') },
  ];

  return (
    <PanelBody
      title={_x('Prefix and suffix', 'Panel title', 'usm')}
      initialOpen={!!(prefix || suffix)}
    >
      {fieldType === 'acf' && (
        <ToggleControl
          label={_x('Use field label as prefix', 'Input label', 'usm')}
          checked={labelAsPrefix}
          onChange={value => setAttributes({ labelAsPrefix: value })}
        />
      )}

      <TextControl
        label={_x('Prefix', 'Input label', 'usm')}
        value={prefix}
        onChange={value => setAttributes({ prefix: value })}
        help={_x('Display before the field value.', 'Input help', 'usm')}
      />

      <TextControl
        label={_x('Suffix', 'Input label', 'usm')}
        value={suffix}
        onChange={value => setAttributes({ suffix: value })}
        help={_x('Display after the field value.', 'Input help', 'usm')}
      />

      <SelectControl
        label={_x('Display layout', 'Input label', 'usm')}
        options={displayLayoutOptions}
        value={displayLayout}
        onChange={value => setAttributes({ displayLayout: value })}
        help={_x(
          'Choose basic layout for prefix, value and suffix. This block does not provide any CSS style for the meta field.',
          'Input help',
          'usm'
        )}
      />
    </PanelBody>
  );
};
