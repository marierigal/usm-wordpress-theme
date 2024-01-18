import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { _x } from '@wordpress/i18n';
import { FC } from 'react';

export interface UrlSettings {
  title: string;
  targetBlank: boolean;
}

export interface UrlSettingsPanelProps {
  settings: UrlSettings;
  onChange: (settings: UrlSettings) => void;
}

export const UrlSettingsPanel: FC<UrlSettingsPanelProps> = ({ settings, onChange }) => {
  return (
    <PanelBody title={_x('URL Settings', 'Panel title', 'usm')}>
      <TextControl
        label={_x('Title', 'Input label', 'usm')}
        value={settings.title}
        onChange={value => onChange({ ...settings, title: value })}
      />

      <ToggleControl
        label={_x('Open in a new tab', 'Input label', 'usm')}
        checked={settings.targetBlank}
        onChange={value => onChange({ ...settings, targetBlank: value })}
      />
    </PanelBody>
  );
};
