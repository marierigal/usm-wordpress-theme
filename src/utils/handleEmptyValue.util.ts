import { __ } from '@wordpress/i18n';
import isObject from 'lodash/isObject';

export interface HandleEmptyValue {
  fieldValue: unknown;
  emptyMessage?: string;
}

export function handleEmptyValue({
  fieldValue,
  emptyMessage = __('No value', 'usm'),
}: HandleEmptyValue): any {
  if (fieldValue && isObject(fieldValue)) {
    return __('This data type is not supported!', 'usm');
  }

  if (!fieldValue && fieldValue !== 0) return emptyMessage;

  return fieldValue;
}
