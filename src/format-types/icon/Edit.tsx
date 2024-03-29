import { BlockControls } from '@wordpress/block-editor';
import { Path, SVG, ToolbarButton, ToolbarGroup } from '@wordpress/components';
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
            icon={
              <SVG
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"
                className="fa-font-awesome fa-w-14"
              >
                <Path
                  fill="currentColor"
                  d="M397.8 32H50.2C22.7 32 0 54.7 0 82.2v347.6C0 457.3 22.7 480 50.2 480h347.6c27.5 0 50.2-22.7 50.2-50.2V82.2c0-27.5-22.7-50.2-50.2-50.2zm-45.4 284.3c0 4.2-3.6 6-7.8 7.8-16.7 7.2-34.6 13.7-53.8 13.7-26.9 0-39.4-16.7-71.7-16.7-23.3 0-47.8 8.4-67.5 17.3-1.2.6-2.4.6-3.6 1.2V385c0 1.8 0 3.6-.6 4.8v1.2c-2.4 8.4-10.2 14.3-19.1 14.3-11.3 0-20.3-9-20.3-20.3V166.4c-7.8-6-13.1-15.5-13.1-26.3 0-18.5 14.9-33.5 33.5-33.5 18.5 0 33.5 14.9 33.5 33.5 0 10.8-4.8 20.3-13.1 26.3v18.5c1.8-.6 3.6-1.2 5.4-2.4 18.5-7.8 40.6-14.3 61.5-14.3 22.7 0 40.6 6 60.9 13.7 4.2 1.8 8.4 2.4 13.1 2.4 22.7 0 47.8-16.1 53.8-16.1 4.8 0 9 3.6 9 7.8v140.3z"
                />
              </SVG>
            }
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
