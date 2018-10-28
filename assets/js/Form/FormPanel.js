'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRows from './FormRows'
import ButtonManager from '../Component/Button/ButtonManager'
import FormRow from './FormRow'

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
            <div className="card-body">
                <FormRow
                    template={template.headerRow}
                    {...otherProps}
                />
                <FormRows
                    template={template.rows}
                    {...otherProps}
                />
            </div>
        </div>
    )
}

FormPanel.propTypes = {
    template: PropTypes.object.isRequired,
}
