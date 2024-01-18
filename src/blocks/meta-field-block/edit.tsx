import { BlockControls, InspectorControls } from '@wordpress/block-editor';
import { BlockEditProps } from '@wordpress/blocks';
import { FC } from 'react';

import {
  EditorBlockStylePanel,
  ImageSettings,
  ImageSettingsPanel,
  MetaField,
  MetaFieldPanel,
  MetaFieldToolbar,
  PrefixSuffixPanel,
  UrlSettings,
  UrlSettingsPanel,
} from '../../components';
import { useDebounceField, useMaybeCurrentPost, useMFBData } from '../../hooks';
import {
  getFieldLabel,
  getFieldNameHelp,
  getFieldValue,
  getRawValue,
  isPlainObject,
} from '../../utils';

import { fieldTypeHelpMessages, fieldTypeOptions } from './constants';

/**
 * @see block.json/attributes
 */
export interface Props {
  textAlign?: string;
  fieldType?: string;
  fieldName?: string;
  fieldSettings?: { type?: string; key?: string };
  hideEmpty?: boolean;
  emptyMessage?: string;
  prefix?: string;
  suffix?: string;
  labelAsPrefix?: boolean;
  displayLayout?: string;
  tagName?: string;
  showOutline?: boolean;
  imageSettings: ImageSettings;
  urlSettings: UrlSettings;
}

/**
 * @see block.json/useContext
 */
export interface Context {
  postId: string;
  postType: string;
}

export const edit: FC<BlockEditProps<Props>> = ({
  attributes: {
    displayLayout,
    emptyMessage,
    fieldName = '',
    fieldSettings = {},
    fieldType = 'acf',
    hideEmpty = false,
    labelAsPrefix = false,
    prefix: customPrefix,
    showOutline = false,
    suffix,
    tagName = 'div',
    textAlign,
    imageSettings,
    urlSettings,
  },
  setAttributes,
  context,
}: {
  attributes: Props;
  setAttributes: (props: Partial<Props>) => void;
  isSelected: boolean;
  context: Partial<Context>;
}) => {
  const { postId, postType } = useMaybeCurrentPost(context.postType, context.postId);

  // Context data
  const { ACFFields, metaFields, restFieldNames, restFieldValue } = useMFBData(
    postType,
    postId,
    fieldName
  );

  // On update field name
  const onFieldNameChange = (newFieldName: string): void => {
    let updatingValue = {
      fieldName: newFieldName,
      fieldSettings: {
        ...fieldSettings,
        type: undefined,
        key: undefined,
      },
    };

    if (fieldType === 'acf') {
      const rawValue = getRawValue({
        fieldType,
        fieldName: newFieldName,
        ACFFields,
      });

      if (isPlainObject(rawValue)) {
        const field = rawValue['field'] ?? {};
        updatingValue = {
          ...updatingValue,
          fieldSettings: {
            ...fieldSettings,
            type: field?.type,
            key: field?.key,
          },
        };
      }
    }

    setAttributes(updatingValue);
  };

  // Field name
  const [tmpFieldName, setTmpFieldName] = useDebounceField<string>(fieldName, onFieldNameChange);

  // Field type
  const type: string = fieldSettings?.type || 'text';

  // Raw value
  const rawValue = getRawValue({
    fieldType,
    fieldName,
    metaFields,
    ACFFields,
    restFieldValue,
  });

  // Field value
  const fieldValue = getFieldValue({
    rawValue,
    fieldType,
    fieldName,
    emptyMessage,
  });

  // Help message for fieldName
  const fieldNameHelp = getFieldNameHelp({
    fieldType,
    metaFields,
    ACFFields,
    restFieldNames,
    onFieldNameChange: setTmpFieldName,
  });

  // Get prefix
  const prefix = customPrefix
    ? customPrefix
    : fieldType === 'acf' && labelAsPrefix
      ? getFieldLabel(rawValue)
      : '';

  return (
    <>
      <BlockControls controls="">
        <MetaFieldToolbar setAttributes={setAttributes} tagName={tagName} textAlign={textAlign} />
      </BlockControls>

      <InspectorControls key="meta-field">
        <MetaFieldPanel
          setAttributes={setAttributes}
          fieldType={fieldType}
          fieldTypeOptions={fieldTypeOptions}
          fieldTypeHelpMessages={fieldTypeHelpMessages}
          fieldName={tmpFieldName}
          setFieldName={setTmpFieldName}
          fieldNameHelp={fieldNameHelp}
          hideEmpty={hideEmpty}
          emptyMessage={emptyMessage}
        />

        {type === 'image' && (
          <ImageSettingsPanel
            settings={imageSettings}
            onChange={settings => setAttributes({ imageSettings: settings })}
          />
        )}

        {type === 'url' && (
          <UrlSettingsPanel
            settings={urlSettings}
            onChange={settings => setAttributes({ urlSettings: settings })}
          />
        )}

        <PrefixSuffixPanel
          setAttributes={setAttributes}
          prefix={customPrefix}
          suffix={suffix}
          labelAsPrefix={labelAsPrefix}
          fieldType={fieldType}
          displayLayout={displayLayout}
        />

        <EditorBlockStylePanel setAttributes={setAttributes} showOutline={showOutline} />
      </InspectorControls>

      <MetaField
        value={fieldValue}
        type={type}
        fieldType={fieldType}
        TagName={tagName}
        textAlign={textAlign}
        prefix={prefix}
        suffix={suffix}
        displayLayout={displayLayout}
        showOutline={showOutline}
        imageSettings={imageSettings}
        urlSettings={urlSettings}
      />
    </>
  );
};
