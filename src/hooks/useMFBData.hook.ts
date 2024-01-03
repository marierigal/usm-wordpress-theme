import { useEntityProp } from '@wordpress/core-data';

export interface MFBData {
  ACFFields: any;
  metaFields: any;
  restFieldNames: any;
  restFieldValue: any;
}

export const useMFBData = (postType: string, postId: string, fieldName: string): MFBData => {
  const kind = 'postType';

  const [ACFFields] = useEntityProp(kind, postType, 'acf', postId);
  const [metaFields] = useEntityProp(kind, postType, 'meta', postId);
  const [restFieldNames] = useEntityProp(kind, postType, 'usm_rest_fields', postId);
  const [restFieldValue] = useEntityProp(kind, postType, fieldName, postId);

  return { ACFFields, metaFields, restFieldNames, restFieldValue };
};
