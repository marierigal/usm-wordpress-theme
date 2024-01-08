import { registerFormatType } from '@wordpress/rich-text';

import { Edit } from './Edit';
import { ICON_NAME, ICON_TAG_NAME, ICON_TITLE } from './constants';

import './style.scss';

registerFormatType(ICON_NAME, {
  title: ICON_TITLE,
  tagName: ICON_TAG_NAME,
  interactive: true,
  className: null,
  // @ts-expect-error: TS2322 because of the wrong declaration type of WPFormat
  attributes: {
    className: 'class',
  },
  edit: Edit,
});
