import { ExternalLink } from '@wordpress/components';
import { createInterpolateElement } from '@wordpress/element';
import { _x } from '@wordpress/i18n';

export const fieldTypeHelpMessages = {
  meta: createInterpolateElement(
    _x(
      "Fields are registered with <RegisterMetaLink /> and 'show_in_rest' setting is enable.",
      'Help message',
      'usm'
    ),
    {
      RegisterMetaLink: (
        <ExternalLink href="https://developer.wordpress.org/reference/functions/register_meta/">
          register_meta
        </ExternalLink>
      ),
    }
  ),
  acf: createInterpolateElement(
    _x(
      "Fields are registered with <ACFLink /> and 'Show in REST API' setting is ON.",
      'Help message',
      'usm'
    ),
    {
      ACFLink: (
        <ExternalLink href="https://wordpress.org/plugins/advanced-custom-fields/">
          Advanced Custom Fields
        </ExternalLink>
      ),
    }
  ),
  rest_field: createInterpolateElement(
    _x(
      "Fields are registered with <RegisterRestFieldLink />. The 'rest field' and the 'custom field' should be the same name. Or adding a filter for the hook `apply_filters( 'meta_field_block_get_block_content', $content, $attributes, $block, $post_id )` to get value for front end.",
      'Help message',
      'usm'
    ),
    {
      RegisterRestFieldLink: (
        <ExternalLink href="https://developer.wordpress.org/reference/functions/register_rest_field/">
          register_rest_field
        </ExternalLink>
      ),
    }
  ),
};

export const fieldTypeOptions = [
  {
    label: _x("Default 'meta'", 'Field type option', 'usm'),
    value: 'meta',
  },
  {
    label: _x('ACF - Advanced Custom Fields', 'Field type option', 'usm'),
    value: 'acf',
  },
  {
    label: _x('Custom rest field', 'Field type option', 'usm'),
    value: 'rest_field',
  },
];
