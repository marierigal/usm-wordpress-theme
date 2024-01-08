import { _x } from '@wordpress/i18n';

import { IconAttributes } from './IconAttributes.interface';

export const ICON_NAME = 'usm/inline-icon';

export const ICON_TITLE = _x('Icon', 'Format title', 'usm');

export const ICON_TAG_NAME = 'i';

export const FA_PREFIX = 'fa-';
export const FA_FIXED_WIDTH = 'fw';

export const DEFAULT_ICON: IconAttributes = {
  style: 'solid',
  icon: 'flag',
  isFixedWidth: false,
  size: '',
};

export const ICON_STYLE_OPTIONS = [
  { label: _x('Solid', 'FA icon style', 'usm'), value: 'solid' },
  { label: _x('Regular', 'FA icon style', 'usm'), value: 'regular' },
  { label: _x('Brands', 'FA icon style', 'usm'), value: 'brands' },
];

export const ICON_SIZE_OPTIONS = [
  { label: _x('2XS', 'FA icon size', 'usm'), value: '2xs' },
  { label: _x('XS', 'FA icon size', 'usm'), value: 'xs' },
  { label: _x('Small', 'FA icon size', 'usm'), value: 'sm' },
  { label: _x('Medium', 'FA icon size', 'usm'), value: '' },
  { label: _x('Large', 'FA icon size', 'usm'), value: 'lg' },
  { label: _x('XL', 'FA icon size', 'usm'), value: 'xl' },
  { label: _x('2XL', 'FA icon size', 'usm'), value: '2xl' },
  { label: _x('1x', 'FA icon size', 'usm'), value: '1x' },
  { label: _x('2x', 'FA icon size', 'usm'), value: '2x' },
  { label: _x('3x', 'FA icon size', 'usm'), value: '3x' },
  { label: _x('4x', 'FA icon size', 'usm'), value: '4x' },
  { label: _x('5x', 'FA icon size', 'usm'), value: '5x' },
  { label: _x('6x', 'FA icon size', 'usm'), value: '6x' },
  { label: _x('7x', 'FA icon size', 'usm'), value: '7x' },
  { label: _x('8x', 'FA icon size', 'usm'), value: '8x' },
  { label: _x('9x', 'FA icon size', 'usm'), value: '9x' },
  { label: _x('10x', 'FA icon size', 'usm'), value: '10x' },
];
