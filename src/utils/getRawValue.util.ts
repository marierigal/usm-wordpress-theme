import { isPlainObject } from './isPlainObject.util';

export interface GetRawValue {
  fieldName?: string;
  fieldType?: string;
  metaFields?: any;
  ACFFields?: any;
  ACFFieldObject?: any;
  restFieldValue?: any;
  settingFieldValue?: any;
}

export function getRawValue({
  fieldName,
  fieldType,
  metaFields,
  ACFFields,
  ACFFieldObject,
  restFieldValue,
  settingFieldValue,
}: GetRawValue): any {
  if (!fieldName) return null;

  if (fieldType === 'meta' && isPlainObject(metaFields)) {
    return metaFields[fieldName] ?? '';
  }

  if (fieldType === 'acf') {
    if (isPlainObject(ACFFields) && (ACFFields[fieldName] ?? false)) {
      return ACFFields[fieldName];
    }

    if (isPlainObject(ACFFieldObject) && ACFFieldObject['field']?.key === fieldName) {
      return ACFFieldObject;
    }
  }

  if (fieldType === 'option') return settingFieldValue;

  return restFieldValue;
}
