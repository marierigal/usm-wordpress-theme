import { AlignmentToolbar } from '@wordpress/block-editor';
import { ToolbarDropdownMenu } from '@wordpress/components';
import { _x } from '@wordpress/i18n';
import { FC } from 'react';

export interface MetaFieldToolbarProps {
  setAttributes: (props: any) => void;
  tagName: string;
  textAlign?: string;
}

export const MetaFieldToolbar: FC<MetaFieldToolbarProps> = ({
  setAttributes,
  tagName,
  textAlign,
}) => {
  const tagNames = [
    ['div', 'section', 'span', 'p', 'figure'],
    ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
  ];

  const TagTitle: FC<{ name: string }> = ({ name }) => (
    <span style={{ fontFamily: 'monospace' }}>{name}</span>
  );

  const tagControls = tagNames.map(values =>
    values.map(value => ({
      title: <TagTitle name={value} />,
      isActive: value === tagName,
      onClick: (): void => setAttributes({ tagName: value }),
      role: 'menuitemradio',
    }))
  );

  return (
    <>
      <ToolbarDropdownMenu
        label={_x('Change tag name', 'Input label', 'usm')}
        text={tagName}
        controls={tagControls as any}
        icon="editor-code"
      />

      <AlignmentToolbar value={textAlign} onChange={value => setAttributes({ textAlign: value })} />
    </>
  );
};
