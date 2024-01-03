import { PanelBody, ToggleControl } from '@wordpress/components';
import { _x } from '@wordpress/i18n';
import { FC } from 'react';

export interface EditorBlockStylePanelProps {
  setAttributes: (props: any) => void;
  showOutline: boolean;
}

export const EditorBlockStylePanel: FC<EditorBlockStylePanelProps> = ({
  setAttributes,
  showOutline,
}) => (
  <PanelBody title={_x('Block style settings', 'Panel title', 'usm')} initialOpen={showOutline}>
    <ToggleControl
      label={_x('Show block outline', 'Input label', 'usm')}
      checked={showOutline}
      onChange={showOutline => setAttributes({ showOutline })}
      help={_x('Highlight the block on the Editor only.', 'Input help', 'usm')}
    />
  </PanelBody>
);
