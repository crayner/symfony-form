'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRows from './FormRows'
import ButtonManager from '../Component/Button/ButtonManager'
import FormRow from './FormRow'
import CollectionType from './CollectionType'

export default function FormPanel(props) {
    const {
        template,
        ...otherProps
    } = props

    function getButtons(){
        return template.buttons.map((button, key) => {
            return (
                <ButtonManager
                    button={button}
                    key={key}
                    {...otherProps}
                />
            )
        })
    }


    let colour = ['dark','success','primary','info','danger','secondary'].includes(template.colour) ? template.colour + ' text-white' : template.colour + ' text-dark'
    let content = ''
    if (template.collection !== false) {
        const form = otherProps.form

        const collection = form.children.find(element => {
            if (element.name === template.collection.form)
                return element
        })

        content = (
            <div className={'card-body'}>
                <FormRow
                    {...otherProps}
                    template={template.headerRow}
                    form={form}
                />
                <CollectionType
                    {...otherProps}
                    template={template.collection}
                    collectionName={template.form}
                    form={collection}
                />
            </div>
        )
    }
    else
        content = (
            <div className="card-body">
                <FormRow
                    {...otherProps}
                    template={template.headerRow}
                />
                <FormRows
                    {...otherProps}
                    template={template.rows}
                />
            </div>
        )

    return (
        <div className={'card card-panel'}>
            <div className={'card-header bg-' + colour}>
                {template.buttons === false ?
                    <h3 className={'card-title d-flex mb-12 justify-content-between'}>{template.label}</h3>
                    :
                    <h3 className={'card-title d-flex mb-12 justify-content-between'}>
                        <span className={'p-6'}>{template.label}</span>
                        <span className={'p-6'}>{getButtons()}</span>
                    </h3>
                }
                <p>{template.description}</p>
            </div>
            {content}
        </div>
    )
}

FormPanel.propTypes = {
    template: PropTypes.object.isRequired,
}
