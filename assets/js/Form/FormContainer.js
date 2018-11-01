'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRows from './FormRows'
import FormPanel from './FormPanel'
import FormRow from './FormRow'
import CollectionType from './CollectionType'

export default function FormContainer(props) {
    const {
        template,
        form,
        ...otherProps
    } = props


    if (template === false)
        return ''

    if (template.panel !== false){
        return (
            <FormPanel
                {...otherProps}
                template={template.panel}
                form={{...form}}
            />
        )
    }

    if (template.collection !== false) {
        const collection = form.children.find(element => {
            if (element.name === template.collection.form)
                return element
        })

        return (
            <div className={template.class}>
                <FormRow
                    {...otherProps}
                    template={template.headerRow}
                    form={{...form}}
                />
                <CollectionType
                    {...otherProps}
                    template={template.collection}
                    collectionName={template.form}
                    form={{...collection}}
                />
            </div>
        )
    }

    return (
        <div className={template.class}>
            <FormRow
                {...otherProps}
                template={template.headerRow}
                form={{...form}}
            />
            <FormRows
                {...otherProps}
                template={template.rows}
                form={{...form}}
            />
        </div>
    )
}

FormContainer.propTypes = {
    form: PropTypes.object.isRequired,
    template: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.bool,
    ]).isRequired,
}
