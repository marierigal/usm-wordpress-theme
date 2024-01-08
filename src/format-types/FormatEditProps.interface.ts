import { RichTextValue } from '@wordpress/rich-text';

export interface FormatEditProps {
  value: RichTextValue;
  activeAttributes: Record<string, string>;
  activeObjectAttributes: Record<string, string>;
  contentRef: { current: HTMLElement };
  context: Record<string, any>;
  isActive: boolean;
  isObjectActive: boolean;
  onChange: (value: RichTextValue) => void;
  onFocus: () => void;
}
