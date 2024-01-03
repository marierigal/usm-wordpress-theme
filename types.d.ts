import { Block, BlockConfiguration, BlockVariation } from '@wordpress/blocks';

declare module '@wordpress/blocks' {
  export function registerBlockType<TAttributes extends Record<string, any> = object>(
    name: string,
    settings: Partial<BlockConfiguration<TAttributes> & { variations: BlockVariation[] }>
  ): Block<TAttributes> | undefined;
}
