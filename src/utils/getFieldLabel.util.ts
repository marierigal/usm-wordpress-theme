import { isPlainObject } from './isPlainObject.util';

export function getFieldLabel(rawValue: unknown): string {
  if (isPlainObject(rawValue)) {
    return rawValue['field']?.label ?? '';
  }

  return '';
}
