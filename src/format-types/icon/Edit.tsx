import { BlockControls } from '@wordpress/block-editor';
import { ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { insertObject } from '@wordpress/rich-text';
import { FC } from 'react';

import { FormatEditProps } from '../FormatEditProps.interface';

import { InlineUI } from './InlineUI';
import { DEFAULT_ICON, ICON_NAME, ICON_TITLE } from './constants';
import { getClassNameFromAttributes } from './getClassNameFromAttributes';

export const Edit: FC<FormatEditProps> = ({
  value,
  isObjectActive,
  activeObjectAttributes,
  contentRef,
  onChange,
  onFocus,
}) => {
  const onClick = (): void => {
    onChange(
      insertObject(value, {
        type: ICON_NAME,
        // @ts-expect-error: TS2353 because of the missing attributes property
        // in declaration type of RichTextFormat
        attributes: {
          className: getClassNameFromAttributes(DEFAULT_ICON),
        },
      })
    );
    onFocus();
  };

  return (
    <>
      <BlockControls controls="">
        <ToolbarGroup>
          <ToolbarButton
            icon="flag"
            title={ICON_TITLE}
            placeholder={ICON_TITLE}
            onClick={onClick}
            isActive={isObjectActive}
          />
        </ToolbarGroup>
      </BlockControls>

      {isObjectActive && (
        <InlineUI
          value={value}
          onChange={onChange}
          activeAttributes={activeObjectAttributes}
          contentRef={contentRef}
        />
      )}
    </>
  );
};
