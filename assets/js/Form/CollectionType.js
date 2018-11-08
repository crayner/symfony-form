'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonManager from '../Component/Button/ButtonManager'
import FormRows from './FormRows'
import firstBy from 'thenby'
import FormRow from './FormRow'

export default function CollectionType(props) {
    const {
        form,
        template,
        getFormElementById,
        ...otherProps
    } = props

    const collection = sortCollection(getFormElementById(form.id))

    const children = collection.children
    let last = null
    Object.keys(children).map(key => {
        let child = children[key]
        last = child.name
    })

    const collectionProps = {
        addButtonHandler: otherProps.addButtonHandler,
        deleteButtonHandler: otherProps.deleteButtonHandler,
        allow_add: collection.allow_add,
        allow_delete: collection.allow_delete,
        allow_up: collection.allow_up,
        allow_down: collection.allow_down,
        allow_duplicate: collection.allow_duplicate,
        collection_buttons: template.buttons,
        first: typeof children[0] !== 'undefined' ? children[0].name : null,
        last: last,
        collection_name: collection.name,
        default_buttons: {
            add: {type: 'add', style: {float: 'right'}, options: {eid: 'id'}},
            delete: {type: 'delete', style: {}, options: {eid: 'id'}},
            up: {type: 'up', style: {}, options: {eid: 'id'}},
            down: {type: 'down', style: {}, options: {eid: 'id'}},
            duplicate: {type: 'duplicate', style: {}, options: {eid: 'id'}},
        },
    }

    const collectionRows = Object.keys(children).map(key => {
            let child = children[key]
            return (
                <FormRows
                    {...otherProps}
                    {...collectionProps}
                    key={key}
                    form={child}
                    template={template.rows}
                />
            )
        }
    )

    const headerRow = (
            <FormRow template={template.headerRow} form={form} {...otherProps} />
        )

    function addButton() {
        if (collection.allow_add) {
            let button = {...collectionProps.default_buttons.add}
            if (typeof template.buttons.add !== 'undefined')
                button = {...template.buttons.add}
            button = Object.assign({id: collection.id + '_add'}, {...button})
            return (
                <ButtonManager
                    {...otherProps}
                    button={button}
                    addButtonHandler={otherProps.addButtonHandler}
                />
            )
        }
        return ''
    }

    function sortCollectionTest(name, v1, v2){
        const child1 = v1.children.find(child => {
            if (child.name === name)
                return child
        })
        const child2 = v2.children.find(child => {
            if (child.name === name)
                return child
        })

        if (typeof child1.data === 'object')
        {
            if (child1.data && typeof child1.data.date !== 'undefined') {
                return child1.data.date > child2.data.date ? 1 : -1
            }
            if (child1.data === null)
                return 1
            console.error('what type of object is this to sort!')
            console.log(child1.data)
        }
        console.log(child1.data < child2.data)
        console.log(name,child1.data,child2.data)
        return child1.data > child2.data ? 1 : -1
    }

    function sortCollection(collection){
        if (typeof template.sortBy !== 'object')
            return collection

        if (collection.children.length === 0)
            return collection

        const sortCriteria = Object.keys(template.sortBy).map(name => {
            return {name: name, orderBy: template.sortBy[name].toUpperCase() === 'DESC' ? -1 : 1 }
        })
        const sortDepth = sortCriteria.length

        if (sortDepth < 1)
            return collection
        else if (sortDepth === 1) {
            collection.children.sort(
                firstBy(function (v1, v2) { return sortCollectionTest(sortCriteria[0]['name'], v1, v2) }, sortCriteria[0]['orderBy'])
            )
            return collection
        }
        else if (sortDepth === 2) {
            collection.children.sort(
                firstBy(function (v1, v2) {
                    return sortCollectionTest(sortCriteria[0]['name'], v1, v2)
                }, sortCriteria[0]['orderBy'])
                    .thenBy(function (v1, v2) {
                        return sortCollectionTest(sortCriteria[1]['name'], v1, v2)
                    }, sortCriteria[1]['orderBy'])
            )
            return collection
        }

        collection.children.sort(
            firstBy(function (v1, v2) { return sortCollectionTest(sortCriteria[0]['name'], v1, v2) }, sortCriteria[0]['orderBy'])
                .thenBy(function (v1, v2) { return sortCollectionTest(sortCriteria[1]['name'], v1, v2) }, sortCriteria[1]['orderBy'])
                .thenBy(function (v1, v2) { return sortCollectionTest(sortCriteria[2]['name'], v1, v2) }, sortCriteria[2]['orderBy'])
        )
        return collection
    }

    return (
        <div id={collection.id} autoComplete={'off'}>
            { headerRow }
            { collectionRows }
            { addButton() }
            <hr />
        </div>
    )
}

CollectionType.propTypes = {
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
    getFormElementById: PropTypes.func.isRequired,
}
