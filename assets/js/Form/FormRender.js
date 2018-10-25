'use strict';

import React from "react"
import PropTypes from 'prop-types'
import Messages from '../Component/Messages/Messages'
import FormRenderTabs from './FormRenderTabs'

export default function FormRender(props) {
    const {
        template,
        ...otherProps
    } = props

    if (template.tabs === false) {
        return (
            <div className={'container'}>
                <Messages
                    {...otherProps}
                />
                <FormContainer
                    template={template.container}
                    {...otherProps}/>
            </div>
        )
    }

    return (
        <div className={'container'}>
            <Messages
                {...otherProps}
            />
            <FormRenderTabs
                template={template.tabs}
                {...otherProps}
            />
        </div>
    )
}

FormRender.propTypes = {
    template: PropTypes.object.isRequired,
}
