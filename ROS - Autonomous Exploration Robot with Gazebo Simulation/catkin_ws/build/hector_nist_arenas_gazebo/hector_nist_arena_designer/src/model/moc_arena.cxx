/****************************************************************************
** Meta object code from reading C++ file 'arena.h'
**
** Created by: The Qt Meta Object Compiler version 63 (Qt 4.8.7)
**
** WARNING! All changes made in this file will be lost!
*****************************************************************************/

#include "../../../../../src/hector_nist_arenas_gazebo/hector_nist_arena_designer/src/model/arena.h"
#if !defined(Q_MOC_OUTPUT_REVISION)
#error "The header file 'arena.h' doesn't include <QObject>."
#elif Q_MOC_OUTPUT_REVISION != 63
#error "This file was generated using the moc from 4.8.7. It"
#error "cannot be used with the include files from this version of Qt."
#error "(The moc has changed too much.)"
#endif

QT_BEGIN_MOC_NAMESPACE
static const uint qt_meta_data_Arena[] = {

 // content:
       6,       // revision
       0,       // classname
       0,    0, // classinfo
       4,   14, // methods
       0,    0, // properties
       0,    0, // enums/sets
       0,    0, // constructors
       0,       // flags
       3,       // signalCount

 // signals: signature, parameters, type, tag, flags
      15,    7,    6,    6, 0x05,
      43,    7,    6,    6, 0x05,
      73,    6,    6,    6, 0x05,

 // slots: signature, parameters, type, tag, flags
      84,    6,    6,    6, 0x08,

       0        // eod
};

static const char qt_meta_stringdata_Arena[] = {
    "Arena\0\0element\0elementAdded(ArenaElement*)\0"
    "elementRemoved(ArenaElement*)\0modified()\0"
    "slotModified()\0"
};

void Arena::qt_static_metacall(QObject *_o, QMetaObject::Call _c, int _id, void **_a)
{
    if (_c == QMetaObject::InvokeMetaMethod) {
        Q_ASSERT(staticMetaObject.cast(_o));
        Arena *_t = static_cast<Arena *>(_o);
        switch (_id) {
        case 0: _t->elementAdded((*reinterpret_cast< ArenaElement*(*)>(_a[1]))); break;
        case 1: _t->elementRemoved((*reinterpret_cast< ArenaElement*(*)>(_a[1]))); break;
        case 2: _t->modified(); break;
        case 3: _t->slotModified(); break;
        default: ;
        }
    }
}

const QMetaObjectExtraData Arena::staticMetaObjectExtraData = {
    0,  qt_static_metacall 
};

const QMetaObject Arena::staticMetaObject = {
    { &QObject::staticMetaObject, qt_meta_stringdata_Arena,
      qt_meta_data_Arena, &staticMetaObjectExtraData }
};

#ifdef Q_NO_DATA_RELOCATION
const QMetaObject &Arena::getStaticMetaObject() { return staticMetaObject; }
#endif //Q_NO_DATA_RELOCATION

const QMetaObject *Arena::metaObject() const
{
    return QObject::d_ptr->metaObject ? QObject::d_ptr->metaObject : &staticMetaObject;
}

void *Arena::qt_metacast(const char *_clname)
{
    if (!_clname) return 0;
    if (!strcmp(_clname, qt_meta_stringdata_Arena))
        return static_cast<void*>(const_cast< Arena*>(this));
    return QObject::qt_metacast(_clname);
}

int Arena::qt_metacall(QMetaObject::Call _c, int _id, void **_a)
{
    _id = QObject::qt_metacall(_c, _id, _a);
    if (_id < 0)
        return _id;
    if (_c == QMetaObject::InvokeMetaMethod) {
        if (_id < 4)
            qt_static_metacall(this, _c, _id, _a);
        _id -= 4;
    }
    return _id;
}

// SIGNAL 0
void Arena::elementAdded(ArenaElement * _t1)
{
    void *_a[] = { 0, const_cast<void*>(reinterpret_cast<const void*>(&_t1)) };
    QMetaObject::activate(this, &staticMetaObject, 0, _a);
}

// SIGNAL 1
void Arena::elementRemoved(ArenaElement * _t1)
{
    void *_a[] = { 0, const_cast<void*>(reinterpret_cast<const void*>(&_t1)) };
    QMetaObject::activate(this, &staticMetaObject, 1, _a);
}

// SIGNAL 2
void Arena::modified()
{
    QMetaObject::activate(this, &staticMetaObject, 2, 0);
}
QT_END_MOC_NAMESPACE
