import {
  Button as Btn,
  ExternalLink,
  Popover,
  SelectControl,
  TextControl,
  ToggleControl,
} from '@wordpress/components';
import { _x } from '@wordpress/i18n';
import { useAnchor } from '@wordpress/rich-text';
import { FC, FormEvent, useState } from 'react';

import { FormatEditProps } from '../FormatEditProps.interface';

import { IconAttributes } from './IconAttributes.interface';
import { ICON_NAME, ICON_SIZE_OPTIONS, ICON_STYLE_OPTIONS, ICON_TAG_NAME } from './constants';
import { getAttributesFromClassName } from './getAttributesFromClassName';
import { getClassNameFromAttributes } from './getClassNameFromAttributes';

type InlineUIProps = Pick<
  FormatEditProps,
  'value' | 'onChange' | 'activeAttributes' | 'contentRef'
>;

export const InlineUI: FC<InlineUIProps> = ({
  value,
  onChange,
  activeAttributes: { className },
  contentRef,
}) => {
  const attributes: IconAttributes = getAttributesFromClassName(className);

  const [style, setStyle] = useState<string>(attributes.style);
  const [icon, setIcon] = useState<string>(attributes.icon);
  const [isFixedWidth, setIsFixedWidth] = useState<boolean>(attributes.isFixedWidth);
  const [size, setSize] = useState<string>(attributes.size);

  const popoverAnchor = useAnchor({
    editableContentElement: contentRef.current,
    // @ts-expect-error: TS2345 because of the wrong declaration type of settings in useAnchor
    settings: { name: ICON_NAME, tagName: ICON_TAG_NAME },
  });

  const submitForm = (event: FormEvent): void => {
    event.preventDefault();

    const newReplacements = value.replacements.slice();

    newReplacements[value.start] = {
      type: ICON_NAME,
      // @ts-expect-error: TS2353 because of the missing attributes property
      // in declaration type of RichTextFormat
      attributes: {
        className: getClassNameFromAttributes({ style, icon, isFixedWidth, size }),
      },
    };

    onChange({ ...value, replacements: newReplacements });
  };

  const removeIcon = (): void => {
    const newReplacements = value.replacements.slice();

    newReplacements[value.start] = {
      type: 'text',
      // @ts-expect-error: TS2353 because of the missing text property
      // in declaration type of RichTextFormat
      text: '',
    };

    onChange({ ...value, replacements: newReplacements });
  };

  return (
    <Popover
      className="usm-icon-format__popover"
      placement="bottom-start"
      focusOnMount={false}
      anchor={popoverAnchor}
    >
      <form className="usm-icon-format__popover__form" onSubmit={submitForm}>
        <SelectControl
          label={_x('Style', 'Input label', 'usm')}
          options={ICON_STYLE_OPTIONS}
          value={style}
          onChange={setStyle}
        />

        <TextControl
          label={_x('Icon', 'Input label', 'usm')}
          help={
            <ExternalLink href="https://fontawesome.com/search?o=r&m=free">
              Font Awesome Icons
            </ExternalLink>
          }
          value={icon}
          onChange={setIcon}
        />

        <SelectControl
          label={_x('Size', 'Input label', 'usm')}
          options={ICON_SIZE_OPTIONS}
          value={size || ''}
          onChange={setSize}
        />

        <ToggleControl
          label={_x('Fixed width', 'Input label', 'usm')}
          checked={isFixedWidth}
          onChange={setIsFixedWidth}
        />

        <div className="usm-icon-format__popover__form__actions">
          <Btn size="compact" variant="secondary" onClick={removeIcon}>
            {_x('Remove', 'Button label', 'usm')}
          </Btn>

          <Btn size="compact" variant="primary" type="submit">
            {_x('Apply', 'Button label', 'usm')}
          </Btn>
        </div>
      </form>
    </Popover>
  );
};
