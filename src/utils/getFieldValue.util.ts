import { __ } from '@wordpress/i18n';
import isNil from 'lodash/isNil';

import { handleEmptyValue } from './handleEmptyValue.util';
import { isPlainObject } from './isPlainObject.util';

export interface GetFieldValue {
  fieldType?: string;
  fieldName?: string;
  emptyMessage?: string;
  rawValue?: unknown;
}

export function getFieldValue({
  fieldType,
  fieldName,
  emptyMessage,
  rawValue,
}: GetFieldValue): any {
  if (!fieldName) {
    return __('This is the Meta Field Block. Please input "Field Name"', 'usm');
  }

  if (!isNil(rawValue)) {
    if (fieldType === 'acf' && isPlainObject(rawValue)) {
      // if field is a select field, return the choice label instead of the value
      const acfFieldType = rawValue['field']?.['type'];
      const acfFieldChoices = rawValue['field']?.['choices'];
      const acfFieldValue = rawValue['value'];

      if (acfFieldType === 'select' && acfFieldChoices[acfFieldValue]) {
        return acfFieldChoices[acfFieldValue];
      } else if (acfFieldType === 'image') {
        return rawValue['value'];
      }

      return rawValue['simple_value_formatted'];
    }

    // Handle empty value
    return handleEmptyValue({ fieldValue: rawValue, emptyMessage });
  }

  return `<code>${fieldName}</code>`;
}
