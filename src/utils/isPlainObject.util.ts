import { default as _isPlainObject } from 'lodash/isPlainObject';

/**
 * Overwrite lodash isPlainObject to use TS type guard
 *
 * @see _.isPlainObject
 */
export function isPlainObject(value?: unknown): value is Record<string, any> {
  return _isPlainObject(value);
}
