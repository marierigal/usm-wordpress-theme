import { useBlockProps } from '@wordpress/block-editor';
import { Spinner } from '@wordpress/components';
import { Attachment, useEntityRecord } from '@wordpress/core-data';
import classnames from 'classnames';
import dompurify from 'dompurify';
import { FC } from 'react';

export interface MetaFieldProps {
  value?: string;
  isLoading?: boolean;
  fieldType: string;
  type?: string;
  className?: string;
  TagName: string;
  textAlign?: string;
  prefix?: string;
  suffix?: string;
  displayLayout?: string;
  showOutline?: boolean;
  width?: string;
  height?: string;
  aspectRatio?: string;
  scale?: string;
  sizeSlug?: string;
}

export const MetaField: FC<MetaFieldProps> = ({
  value,
  isLoading,
  fieldType,
  type,
  className,
  TagName,
  textAlign,
  prefix,
  suffix,
  displayLayout,
  showOutline,
  width,
  height,
  aspectRatio,
  scale,
  sizeSlug = 'medium',
}) => {
  const attachmentResolution =
    type === 'image' && useEntityRecord<Attachment<'view'>>('postType', 'attachment', value || '');

  const InnerTag = TagName === 'div' ? 'div' : 'span';

  const PrefixElement = prefix && (
    <InnerTag className="prefix">{dompurify.sanitize(prefix)}</InnerTag>
  );

  const SuffixElement = suffix && (
    <InnerTag className="suffix">{dompurify.sanitize(suffix)}</InnerTag>
  );

  const classNames = classnames(className, {
    [`is-${type}-field`]: type,
    [`is-${fieldType}-field`]: fieldType,
    [`has-text-align-${textAlign}`]: textAlign,
    [`is-display-${displayLayout}`]: displayLayout,
  });

  const showOutlineStyles = {
    minHeight: '1rem',
    outline: '1px dashed',
  };

  if (isLoading || (type === 'image' && attachmentResolution && attachmentResolution.isResolving)) {
    return (
      <TagName
        {...useBlockProps({
          className: classNames,
          style: showOutline ? showOutlineStyles : null,
        })}
      >
        <Spinner />
      </TagName>
    );
  }

  if (type === 'image' && attachmentResolution && attachmentResolution.record) {
    const sizeProps = attachmentResolution.record.media_details.sizes[
      sizeSlug as any
    ] as unknown as Record<string, any> | undefined;

    if (sizeProps) {
      const imageWrapperStyles = {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        aspectRatio: aspectRatio || null,
        width: height && aspectRatio ? '100%' : width || null,
        height: width && aspectRatio ? '100%' : height || null,
      };

      return (
        <TagName
          {...useBlockProps({
            className: classNames,
            style: {
              ...(showOutline ? showOutlineStyles : null),
              ...imageWrapperStyles,
            },
          })}
        >
          {PrefixElement}
          <img
            className={`attachment-${sizeSlug} size-${sizeSlug}`}
            style={{
              aspectRatio,
              objectFit: scale as any,
              width: height && aspectRatio ? '100%' : width,
              height: width && aspectRatio ? '100%' : height,
            }}
            src={sizeProps.source_url}
            alt=""
            decoding="async"
            loading="lazy"
          />
          {SuffixElement}
        </TagName>
      );
    }
  }

  return (
    <TagName
      {...useBlockProps({
        className: classNames,
        style: showOutline ? showOutlineStyles : null,
      })}
    >
      {PrefixElement}
      {(value && dompurify.sanitize(value)) || ''}
      {SuffixElement}
    </TagName>
  );
};
