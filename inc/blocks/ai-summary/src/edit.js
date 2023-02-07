import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import { useBlockProps, RichText, BlockControls } from '@wordpress/block-editor';
import { TextControl, ToolbarGroup, ToolbarButton, Popover, Spinner } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

export default function Edit({attributes, setAttributes}) {
	const [ isAudienceInputVisible, setAudienceInputVisible ] = useState( false );
	const [ isLoading, setIsLoading ] = useState( false );
	const [ defaultAudience ] = useEntityProp( 'root', 'site', 'wpaitb_default_audience' );
	const [ length ] = useEntityProp( 'root', 'site', 'wpaitb_summary_length' );

	const content = useSelect((select) => {
		return select('core/editor').getEditedPostContent();
	});

	//Load initial summary
	useEffect(() => {
		if(!attributes.summary) {
			generateSummary(defaultAudience, content);
		}
	}, []);

	const blockProps = useBlockProps();

	const toggleAudienceInputVisible = () => {
		setAudienceInputVisible( ( state ) => ! state );
	};

	const showLoadingSpinner = () => {
		return !attributes.summary || isLoading;
	}

	const generateSummary = (audience, content) => {
		setIsLoading(true);
		apiFetch({
			path: '/wp-ai-toolbox/v1/summarize',
			method: 'POST',
			data: {
				audience,
				content,
				length
			}
		}).then((summary) => {
			if(summary.summary?.length === 0) {
				//Something happened and OpenAI sent nothing, debugging this
				generateSummary(audience, content);
			}
			if(summary.errors) {
				setAttributes({summary: summary.errors?.openai_error[0]});
				setIsLoading(false);
			} else {
				setAttributes({summary: summary.summary.trim()});
				setIsLoading(false);
			}
		});
	}

	const regenerateSummary = () => {
		let audience = attributes.audience;

		if(!audience || audience.length === 0) {
			audience = defaultAudience;
		}

		generateSummary(audience, content);
	}

	const { summary, audience } = attributes;
	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						icon="admin-users"
						label={__('Audience', 'wp-ai-toolbox')}
						onClick={toggleAudienceInputVisible}
						value={audience}
						disabled={isLoading}
					>
					</ToolbarButton>
					<ToolbarButton
						icon="welcome-write-blog"
						label={__('Generate Summary', 'wp-ai-toolbox')}
						onClick={regenerateSummary}
						disabled={isLoading || !audience || audience.length === 0}
					/>
					{isAudienceInputVisible &&
						<Popover onClose={() => { setAudienceInputVisible(false) }} >
							<TextControl
								onChange={(audience) => setAttributes({audience})}
								value={audience ? audience : defaultAudience}
								style={{width: '300px'}}
								__nextHasNoMarginBottom
							/>
						</Popover>
					}
				</ToolbarGroup>
			</BlockControls>
			{showLoadingSpinner() && <div { ...blockProps }>{__('Please wait, generating a new summary.', 'wp-ai-toolbox')}<Spinner /></div>}
			{!showLoadingSpinner() && <RichText
				{ ...blockProps }
				onChange={(summary) => setAttributes({summary})}
				tagName="p"
				value={summary}
				placeholder={__('Choose your audience above to generate your summary', 'wp-ai-toolbox')}
			/>}

		</>
	);
}
