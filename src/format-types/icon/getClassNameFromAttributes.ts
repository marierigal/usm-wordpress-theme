import { IconAttributes } from './IconAttributes.interface';
import { FA_FIXED_WIDTH, FA_PREFIX } from './constants';

export function getClassNameFromAttributes({
  size,
  icon,
  style,
  isFixedWidth,
}: IconAttributes): string {
  const classNames = [FA_PREFIX + style, FA_PREFIX + icon];

  if (size) classNames.push(FA_PREFIX + size);

  if (isFixedWidth) classNames.push(FA_PREFIX + FA_FIXED_WIDTH);

  return classNames.join(' ');
}
