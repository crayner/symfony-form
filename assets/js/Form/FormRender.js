'use strict';

import React from "react"
import PropTypes from 'prop-types'
import Messages from '../Component/Messages/Messages'
import FormRenderTabs from './FormRenderTabs'
import FormContainer from './FormContainer'

export default function FormRender(props) {
    const {
        template,
        ...otherProps
    } = props

    if (template.container !== false) {
        return (
            <div className={'container'}>
                <Messages
                    {...otherProps}
                />
                <FormContainer
                    {...otherProps}
                    template={template.container}
                />
            </div>
        )
    }

    return (
        <div className={'container'}>
            <Messages
                {...otherProps}
            />
            <FormRenderTabs
                {...otherProps}
                template={template.tabs}
            />
        </div>
    )
}

FormRender.propTypes = {
    template: PropTypes.object.isRequired,
}
