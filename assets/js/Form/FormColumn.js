'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormContainer from './FormContainer'
import FormRows from './FormRows'
import FormElementSelect from './FormElementSelect'
import ButtonManager from '../Component/Button/ButtonManager'
import Parser from 'html-react-parser';

export default function FormColumn(props) {
    const {
        template,
        allow_up,
        allow_down,
        allow_delete,
        allow_duplicate,
        first,
        last,
        form,
        collection_buttons,
        default_buttons,
        callUrl,
        ...otherProps
    } = props

    if (template.container !== false)
        return (
            <div className={typeof template.class === 'string' ? template.class : undefined}>
                <FormContainer
                    {...otherProps}
                    template={template.container}
                    callUrl={callUrl}
                    form={form}
                />
            </div>
        )

    if (template.rows !== false)
        return (
            <div className={template.class}>
                <FormRows
                    {...otherProps}
                    template={template.rows}
                    form={form}
                    callUrl={callUrl}
                />
            </div>
        )

    let buttons = []
    if (template.buttons !== false) {
        buttons = Object.keys(template.buttons).map(key => {
            return (
                <ButtonManager
                    {...otherProps}
                    key={key}
                    button={{...template.buttons[key]}}
                />
            )
        })
    }

    if (template.collection_actions !== false){
        let button = {}
        if (allow_delete){
            button = {...default_buttons.delete}
            if (typeof collection_buttons.delete !== 'undefined')
                button = {...collection_buttons.delete}
            button.row = form
            buttons.unshift(buildButton(button, 'delete'))
        }
        if (allow_duplicate)
        {
            button = {...default_buttons.duplicate}
            if (typeof collection_buttons.duplicate !== 'undefined')
                button = {...collection_buttons.duplicate}
            buttons.unshift(buildButton(button, 'duplicate'))
        }
        if (allow_down && last !== form.name)
        {
            button = {...default_buttons.down}
            if (typeof collection_buttons.down !== 'undefined')
                button = {...collection_buttons.down}
            buttons.unshift(buildButton(button, 'down'))
        }
        if (allow_up && first !== form.name)
        {
            button = {...default_buttons.up}
            if (typeof collection_buttons.up !== 'undefined')
                button = {...collection_buttons.up}
            buttons.unshift(buildButton(button, 'up'))
        }
    }

    if (template.form !== false) {
        const formElements = Object.keys(template.form).map(key => {
            const style = template.form[key]
            return (
                <FormElementSelect
                    {...otherProps}
                    style={style}
                    name={key}
                    key={key}
                    form={{...form}}
                    template={template}
                    callUrl={callUrl}
                />
            )
        })

        return (
            <div className={typeof template.class === 'string' ? template.class : undefined}>
                {formElements}
                {buttons}
            </div>
        )
    }

    function buildButton(button, key){
        return (
            <ButtonManager
                {...otherProps}
                button={button}
                key={key}
            />
        )
    }

    if (typeof template.label !== 'string')
        template.label = ''

    let attr = {}

    if (template.style !== false)
        attr.style = {...template.style}

    if (template.onClick !== false) {
        attr.onClick = (e) => callUrl(e,template.onClick)
    }

    if (typeof template.class === 'string')
        attr.className = template.class


    return (
        <div {...attr}>
            {Parser(template.label)}
            <span>{buttons}</span>
        </div>
    )
}

FormColumn.propTypes = {
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
    callUrl: PropTypes.func.isRequired,
    collection_buttons: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]),
    default_buttons: PropTypes.object,
    allow_delete: PropTypes.bool,
    allow_up: PropTypes.bool,
    allow_down: PropTypes.bool,
    allow_duplicate: PropTypes.bool,
    first: PropTypes.string,
    last: PropTypes.string,
}

FormColumn.defaultProps = {
    allow_delete: false,
    allow_up: false,
    allow_down: false,
    allow_duplicate: false,
    collection_buttons: {},
}

