/****************************************************************************
** Meta object code from reading C++ file 'arenaelement.h'
**
** Created by: The Qt Meta Object Compiler version 63 (Qt 4.8.7)
**
** WARNING! All changes made in this file will be lost!
*****************************************************************************/

#include "../../../../../src/hector_nist_arenas_gazebo/hector_nist_arena_designer/src/model/arenaelement.h"
#if !defined(Q_MOC_OUTPUT_REVISION)
#error "The header file 'arenaelement.h' doesn't include <QObject>."
#elif Q_MOC_OUTPUT_REVISION != 63
#error "This file was generated using the moc from 4.8.7. It"
#error "cannot be used with the include files from this version of Qt."
#error "(The moc has changed too much.)"
#endif

QT_BEGIN_MOC_NAMESPACE
static const uint qt_meta_data_ArenaElement[] = {

 // content:
       6,       // revision
       0,       // classname
       0,    0, // classinfo
       3,   14, // methods
       0,    0, // properties
       0,    0, // enums/sets
       0,    0, // constructors
       0,       // flags
       3,       // signalCount

 // signals: signature, parameters, type, tag, flags
      26,   14,   13,   13, 0x05,
      76,   59,   13,   13, 0x05,
     119,  111,   13,   13, 0x05,

       0        // eod
};

static const char qt_meta_stringdata_ArenaElement[] = {
    "ArenaElement\0\0element,pos\0"
    "posChanged(ArenaElement*,QPoint)\0"
    "element,rotation\0rotationChanged(ArenaElement*,int)\0"
    "element\0modified(ArenaElement*)\0"
};

void ArenaElement::qt_static_metacall(QObject *_o, QMetaObject::Call _c, int _id, void **_a)
{
    if (_c == QMetaObject::InvokeMetaMethod) {
        Q_ASSERT(staticMetaObject.cast(_o));
        ArenaElement *_t = static_cast<ArenaElement *>(_o);
        switch (_id) {
        case 0: _t->posChanged((*reinterpret_cast< ArenaElement*(*)>(_a[1])),(*reinterpret_cast< QPoint(*)>(_a[2]))); break;
        case 1: _t->rotationChanged((*reinterpret_cast< ArenaElement*(*)>(_a[1])),(*reinterpret_cast< int(*)>(_a[2]))); break;
        case 2: _t->modified((*reinterpret_cast< ArenaElement*(*)>(_a[1]))); break;
        default: ;
        }
    }
}

const QMetaObjectExtraData ArenaElement::staticMetaObjectExtraData = {
    0,  qt_static_metacall 
};

const QMetaObject ArenaElement::staticMetaObject = {
    { &QObject::staticMetaObject, qt_meta_stringdata_ArenaElement,
      qt_meta_data_ArenaElement, &staticMetaObjectExtraData }
};

#ifdef Q_NO_DATA_RELOCATION
const QMetaObject &ArenaElement::getStaticMetaObject() { return staticMetaObject; }
#endif //Q_NO_DATA_RELOCATION

const QMetaObject *ArenaElement::metaObject() const
{
    return QObject::d_ptr->metaObject ? QObject::d_ptr->metaObject : &staticMetaObject;
}

void *ArenaElement::qt_metacast(const char *_clname)
{
    if (!_clname) return 0;
    if (!strcmp(_clname, qt_meta_stringdata_ArenaElement))
        return static_cast<void*>(const_cast< ArenaElement*>(this));
    return QObject::qt_metacast(_clname);
}

int ArenaElement::qt_metacall(QMetaObject::Call _c, int _id, void **_a)
{
    _id = QObject::qt_metacall(_c, _id, _a);
    if (_id < 0)
        return _id;
    if (_c == QMetaObject::InvokeMetaMethod) {
        if (_id < 3)
            qt_static_metacall(this, _c, _id, _a);
        _id -= 3;
    }
    return _id;
}

// SIGNAL 0
void ArenaElement::posChanged(ArenaElement * _t1, QPoint _t2)
{
    void *_a[] = { 0, const_cast<void*>(reinterpret_cast<const void*>(&_t1)), const_cast<void*>(reinterpret_cast<const void*>(&_t2)) };
    QMetaObject::activate(this, &staticMetaObject, 0, _a);
}

// SIGNAL 1
void ArenaElement::rotationChanged(ArenaElement * _t1, int _t2)
{
    void *_a[] = { 0, const_cast<void*>(reinterpret_cast<const void*>(&_t1)), const_cast<void*>(reinterpret_cast<const void*>(&_t2)) };
    QMetaObject::activate(this, &staticMetaObject, 1, _a);
}

// SIGNAL 2
void ArenaElement::modified(ArenaElement * _t1)
{
    void *_a[] = { 0, const_cast<void*>(reinterpret_cast<const void*>(&_t1)) };
    QMetaObject::activate(this, &staticMetaObject, 2, _a);
}
QT_END_MOC_NAMESPACE
