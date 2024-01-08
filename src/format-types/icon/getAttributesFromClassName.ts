import { IconAttributes } from './IconAttributes.interface';
import {
  DEFAULT_ICON,
  FA_FIXED_WIDTH,
  FA_PREFIX,
  ICON_SIZE_OPTIONS,
  ICON_STYLE_OPTIONS,
} from './constants';

export function getAttributesFromClassName(className: string): IconAttributes {
  const classNames = className.split(' ');

  const attributes = DEFAULT_ICON;

  classNames.forEach(className => {
    if (className.startsWith(FA_PREFIX)) {
      const value = className.replace(FA_PREFIX, '');

      if (value === FA_FIXED_WIDTH) {
        attributes.isFixedWidth = true;
      } else if (ICON_STYLE_OPTIONS.some(option => option.value === value)) {
        attributes.style = value;
      } else if (ICON_SIZE_OPTIONS.some(option => option.value === value)) {
        attributes.size = value;
      } else {
        attributes.icon = value;
      }
    }
  });

  return attributes;
}
