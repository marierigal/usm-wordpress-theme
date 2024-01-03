import { useSelect } from '@wordpress/data';
import { store as editorStore } from '@wordpress/editor';

export interface MaybeCurrentPost {
  postType: string;
  postId: string;
}

export const useMaybeCurrentPost = (postType?: string, postId?: string): MaybeCurrentPost => {
  const { getCurrentPostId, getCurrentPostType } = useSelect(editorStore, []) as any;

  // If there is no post type, post id from the context then fallback to current post type, post id.
  if (!postType) {
    postType = getCurrentPostType() as string;
  }

  if (!postId) {
    postId = getCurrentPostId() as string;
  }

  return { postType, postId };
};
