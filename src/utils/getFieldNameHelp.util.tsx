import { __ } from '@wordpress/i18n';
import omit from 'lodash/omit';
import { ReactNode } from 'react';

import { isPlainObject } from './isPlainObject.util';

export interface GetFieldNameHelp {
  fieldType?: string;
  metaFields?: object;
  ACFFields?: object;
  restFieldNames?: string[];
  settingFieldNames?: string[];
  onFieldNameChange?: (value: string) => void;
}

export function getFieldNameHelp({
  fieldType,
  metaFields,
  ACFFields,
  restFieldNames,
  settingFieldNames,
  onFieldNameChange = (): void => {},
}: GetFieldNameHelp): ReactNode {
  const fieldNameHelp: ReactNode = __('Input the field name.', 'usm') + ' ';
  const coreMetaFields = ['footnotes'];

  let suggestedNames;
  if (fieldType === 'meta' && isPlainObject(metaFields)) {
    suggestedNames = Object.keys(omit(metaFields, coreMetaFields));
  } else if (fieldType === 'acf' && isPlainObject(ACFFields)) {
    suggestedNames = Object.keys(ACFFields);
  } else if (fieldType === 'rest_field') {
    suggestedNames = restFieldNames;
  } else if (fieldType === 'option') {
    suggestedNames = settingFieldNames;
  }

  let suggestedValue = null;
  if (suggestedNames && suggestedNames.length) {
    suggestedValue = suggestedNames.map((item, index) => (
      <>
        <code key={item} onClick={() => onFieldNameChange(item)} style={{ cursor: 'pointer' }}>
          {item}
        </code>
        {index < suggestedNames.length - 1 ? ', ' : ''}
      </>
    ));
  }

  if (suggestedValue) {
    return (
      <>
        {fieldNameHelp}
        {__('Suggested values:', 'usm')}
        <span style={{ lineHeight: 2 }}> {suggestedValue}</span>
      </>
    );
  }

  if (fieldType === 'acf') {
    return (
      <>
        {fieldNameHelp}
        {__(
          'Using the field key as the field name can help the block access the field information without an object id.',
          'usm'
        )}
      </>
    );
  }

  return fieldNameHelp;
}
