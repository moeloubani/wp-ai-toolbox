import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { summary } = attributes;
	return (
		<RichText.Content { ...useBlockProps.save() } value={ summary } tagName="p"/>
	);
}
