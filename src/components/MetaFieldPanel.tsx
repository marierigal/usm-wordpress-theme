import { PanelBody, SelectControl, TextControl, ToggleControl } from '@wordpress/components';
import { _x } from '@wordpress/i18n';
import { FC, ReactNode } from 'react';

export interface MetaFieldPanelProps {
  fieldType: string;
  fieldTypeOptions: { label: string; value: string }[];
  fieldTypeHelpMessages: Record<string, ReactNode>;
  setAttributes: (props: any) => void;
  fieldName: string;
  setFieldName: (value: string) => void;
  fieldNameHelp?: ReactNode;
  hideEmpty: boolean;
  emptyMessage?: string;
}

export const MetaFieldPanel: FC<MetaFieldPanelProps> = ({
  fieldType,
  fieldTypeOptions,
  fieldTypeHelpMessages,
  setAttributes,
  fieldName,
  setFieldName,
  fieldNameHelp,
  hideEmpty,
  emptyMessage = '',
}) => {
  return (
    <PanelBody title={_x('Meta field settings', 'Panel title', 'usm')}>
      <SelectControl
        label={_x('Field type', 'Input label', 'usm')}
        value={fieldType}
        onChange={value => setAttributes({ fieldType: value })}
        options={fieldTypeOptions}
        help={fieldTypeHelpMessages[fieldType] ?? _x('Choose a field type', 'Input help', 'usm')}
      />

      <TextControl
        autoComplete="off"
        label={_x('Field name', 'Input label', 'usm')}
        value={fieldName}
        onChange={setFieldName}
        help={fieldNameHelp}
      />

      <ToggleControl
        label={_x('Hide block if the value is empty', 'Input label', 'usm')}
        checked={hideEmpty}
        onChange={hideEmpty => setAttributes({ hideEmpty })}
      />

      {!hideEmpty && (
        <TextControl
          label={_x('Empty message', 'Input label', 'usm')}
          value={emptyMessage}
          onChange={emptyMessage => setAttributes({ emptyMessage })}
          help={_x('Display this text if the value is empty.', 'Input help', 'usm')}
        />
      )}
    </PanelBody>
  );
};
