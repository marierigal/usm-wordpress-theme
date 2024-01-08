import { FC } from 'react';

import { FormatEditProps } from './FormatEditProps.interface';

export interface FormatSettings {
  name: string;
  tagName: keyof HTMLElementTagNameMap & string;
  className: string | null;
  title: string;
  attributes: Record<string, string>;
  edit: FC<FormatEditProps>;
  keywords?: string[];
  object?: boolean;
  interactive?: boolean;
}
