/****************************************************************************
** Meta object code from reading C++ file 'arenaview.h'
**
** Created by: The Qt Meta Object Compiler version 63 (Qt 4.8.7)
**
** WARNING! All changes made in this file will be lost!
*****************************************************************************/

#include "../../../../../src/hector_nist_arenas_gazebo/hector_nist_arena_designer/src/ui/arenaview.h"
#if !defined(Q_MOC_OUTPUT_REVISION)
#error "The header file 'arenaview.h' doesn't include <QObject>."
#elif Q_MOC_OUTPUT_REVISION != 63
#error "This file was generated using the moc from 4.8.7. It"
#error "cannot be used with the include files from this version of Qt."
#error "(The moc has changed too much.)"
#endif

QT_BEGIN_MOC_NAMESPACE
static const uint qt_meta_data_ArenaView[] = {

 // content:
       6,       // revision
       0,       // classname
       0,    0, // classinfo
       3,   14, // methods
       0,    0, // properties
       0,    0, // enums/sets
       0,    0, // constructors
       0,       // flags
       0,       // signalCount

 // slots: signature, parameters, type, tag, flags
      11,   10,   10,   10, 0x0a,
      24,   10,   10,   10, 0x0a,
      38,   10,   10,   10, 0x08,

       0        // eod
};

static const char qt_meta_stringdata_ArenaView[] = {
    "ArenaView\0\0slotZoomIn()\0slotZoomOut()\0"
    "slotGridPaintingDisabled()\0"
};

void ArenaView::qt_static_metacall(QObject *_o, QMetaObject::Call _c, int _id, void **_a)
{
    if (_c == QMetaObject::InvokeMetaMethod) {
        Q_ASSERT(staticMetaObject.cast(_o));
        ArenaView *_t = static_cast<ArenaView *>(_o);
        switch (_id) {
        case 0: _t->slotZoomIn(); break;
        case 1: _t->slotZoomOut(); break;
        case 2: _t->slotGridPaintingDisabled(); break;
        default: ;
        }
    }
    Q_UNUSED(_a);
}

const QMetaObjectExtraData ArenaView::staticMetaObjectExtraData = {
    0,  qt_static_metacall 
};

const QMetaObject ArenaView::staticMetaObject = {
    { &QGraphicsView::staticMetaObject, qt_meta_stringdata_ArenaView,
      qt_meta_data_ArenaView, &staticMetaObjectExtraData }
};

#ifdef Q_NO_DATA_RELOCATION
const QMetaObject &ArenaView::getStaticMetaObject() { return staticMetaObject; }
#endif //Q_NO_DATA_RELOCATION

const QMetaObject *ArenaView::metaObject() const
{
    return QObject::d_ptr->metaObject ? QObject::d_ptr->metaObject : &staticMetaObject;
}

void *ArenaView::qt_metacast(const char *_clname)
{
    if (!_clname) return 0;
    if (!strcmp(_clname, qt_meta_stringdata_ArenaView))
        return static_cast<void*>(const_cast< ArenaView*>(this));
    return QGraphicsView::qt_metacast(_clname);
}

int ArenaView::qt_metacall(QMetaObject::Call _c, int _id, void **_a)
{
    _id = QGraphicsView::qt_metacall(_c, _id, _a);
    if (_id < 0)
        return _id;
    if (_c == QMetaObject::InvokeMetaMethod) {
        if (_id < 3)
            qt_static_metacall(this, _c, _id, _a);
        _id -= 3;
    }
    return _id;
}
QT_END_MOC_NAMESPACE
