import {
  __experimentalToggleGroupControl as ToggleGroupControl,
  __experimentalToggleGroupControlOption as ToggleGroupControlOption,
  __experimentalUnitControl as UnitControl,
  Flex,
  PanelBody,
  SelectControl,
} from '@wordpress/components';
import { _x } from '@wordpress/i18n';
import { FC, useMemo } from 'react';

export interface ImageSettings {
  width?: string;
  height?: string;
  aspectRatio: string;
  scale?: string;
  sizeSlug: string;
}

export interface ImageDimensionPanelProps {
  settings: ImageSettings;
  onChange: (settings: ImageSettings) => void;
}

export const ImageSettingsPanel: FC<ImageDimensionPanelProps> = ({ settings, onChange }) => {
  const { width, height, aspectRatio, scale, sizeSlug } = settings;

  const ratioOptions = [
    { value: 'auto', label: _x('Original', 'Ratio option', 'usm') },
    { value: '1', label: _x('Square - 1:1', 'Ratio option', 'usm') },
    { value: '4 / 3', label: _x('Standard - 4:3', 'Ratio option', 'usm') },
    { value: '3 / 4', label: _x('Portrait - 3:4', 'Ratio option', 'usm') },
    { value: '3 / 2', label: _x('Classic - 3:2', 'Ratio option', 'usm') },
    { value: '2 / 3', label: _x('Portrait classic - 2:3', 'Ratio option', 'usm') },
    { value: '16 / 9', label: _x('Wide - 16:9', 'Ratio option', 'usm') },
    { value: '9 / 16', label: _x('High - 9:16', 'Ratio option', 'usm') },
    { value: 'custom', label: _x('Custom', 'Ratio option', 'usm'), hidden: true, disabled: true },
  ];

  const scaleOptions = [
    {
      value: 'cover',
      label: _x('Cover', 'Scale option', 'usm'),
      help: _x('Image covers the space evenly.', 'Input help', 'usm'),
    },
    {
      value: 'contain',
      label: _x('Contain', 'Scale option', 'usm'),
      help: _x('Image is contained without distortion.', 'Input help', 'usm'),
    },
  ];

  const sizeOptions = [
    { value: 'thumbnail', label: _x('Thumbnail', 'Image size option', 'usm') },
    { value: 'medium', label: _x('Medium', 'Image size option', 'usm') },
    { value: 'large', label: _x('Large', 'Image size option', 'usm') },
    { value: 'full', label: _x('Full Size', 'Image size option', 'usm') },
  ];

  const scaleHelp = useMemo(() => {
    return scaleOptions.reduce((acc: Record<string, string>, option) => {
      acc[option.value] = option.help;
      return acc;
    }, {});
  }, [scaleOptions]);

  const units = [{ value: 'px', label: 'px', default: 0 }];

  return (
    <PanelBody title={_x('Image style settings', 'Panel title', 'usm')}>
      <SelectControl
        label={_x('Ratio', 'Input label', 'usm')}
        options={ratioOptions}
        value={aspectRatio}
        onChange={value =>
          onChange({
            ...settings,
            aspectRatio: value,
            scale: value === '' ? '' : scale || 'cover',
            height: value !== '' && width && height ? '' : height,
          })
        }
      />

      {aspectRatio !== 'auto' && scale && (
        <ToggleGroupControl
          label={_x('Apply', 'Input label', 'usm')}
          value={scale}
          help={scaleHelp[scale]}
          onChange={value =>
            onChange({
              ...settings,
              scale: value as unknown as string | undefined,
            })
          }
          isBlock
        >
          {scaleOptions.map(({ label, value }) => (
            <ToggleGroupControlOption key={value} value={value} label={label} />
          ))}
        </ToggleGroupControl>
      )}

      <Flex direction="row" align="top">
        <UnitControl
          label={_x('Width', 'Input label', 'usm')}
          value={width}
          placeholder={_x('Auto', 'Input placeholder', 'usm')}
          units={units}
          min={0}
          onChange={value =>
            onChange({
              ...settings,
              width: value,
              aspectRatio: value && height ? 'custom' : aspectRatio,
            })
          }
        />

        <UnitControl
          label={_x('Height', 'Input label', 'usm')}
          value={`${height}px`}
          placeholder={_x('Auto', 'Input placeholder', 'usm')}
          units={units}
          min={0}
          onChange={value =>
            onChange({
              ...settings,
              height: value,
              aspectRatio: width && value ? 'custom' : aspectRatio,
            })
          }
        />
      </Flex>

      <SelectControl
        label={_x('Resolution', 'Input label', 'usm')}
        options={sizeOptions}
        value={sizeSlug}
        onChange={value => onChange({ ...settings, sizeSlug: value })}
        help={_x('Choose a size for the source image.', 'Input help', 'usm')}
      />
    </PanelBody>
  );
};
