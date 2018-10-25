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

    if (template.panel !== false){
        return (
            <FormPanel
                template={template.panel}
                form={{...form}}
                {...otherProps}
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
                    template={template.headerRow}
                    form={{...form}}
                    {...otherProps}
                />
                <CollectionType
                    template={template.collection}
                    collectionName={template.form}
                    form={{...collection}}
                    {...otherProps}
                />
            </div>
        )
    }

    return (
        <div className={template.class}>
            <FormRow
                template={template.headerRow}
                form={{...form}}
                {...otherProps}
            />
            <FormRows
                template={template.rows}
                form={{...form}}
                {...otherProps}
            />
        </div>
    )
}

FormContainer.propTypes = {
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
}
