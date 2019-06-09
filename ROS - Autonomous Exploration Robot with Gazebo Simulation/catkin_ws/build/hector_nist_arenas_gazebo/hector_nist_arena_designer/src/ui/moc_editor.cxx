/****************************************************************************
** Meta object code from reading C++ file 'editor.h'
**
** Created by: The Qt Meta Object Compiler version 63 (Qt 4.8.7)
**
** WARNING! All changes made in this file will be lost!
*****************************************************************************/

#include "../../../../../src/hector_nist_arenas_gazebo/hector_nist_arena_designer/src/ui/editor.h"
#if !defined(Q_MOC_OUTPUT_REVISION)
#error "The header file 'editor.h' doesn't include <QObject>."
#elif Q_MOC_OUTPUT_REVISION != 63
#error "This file was generated using the moc from 4.8.7. It"
#error "cannot be used with the include files from this version of Qt."
#error "(The moc has changed too much.)"
#endif

QT_BEGIN_MOC_NAMESPACE
static const uint qt_meta_data_Editor[] = {

 // content:
       6,       // revision
       0,       // classname
       0,    0, // classinfo
      14,   14, // methods
       0,    0, // properties
       0,    0, // enums/sets
       0,    0, // constructors
       0,       // flags
       0,       // signalCount

 // slots: signature, parameters, type, tag, flags
       8,    7,    7,    7, 0x0a,
      30,    7,    7,    7, 0x0a,
      59,    7,    7,    7, 0x0a,
      78,   72,    7,    7, 0x0a,
     109,    7,    7,    7, 0x0a,
     119,    7,    7,    7, 0x0a,
     130,    7,    7,    7, 0x0a,
     141,    7,    7,    7, 0x0a,
     154,    7,    7,    7, 0x0a,
     167,    7,    7,    7, 0x0a,
     183,    7,    7,    7, 0x0a,
     206,    7,    7,    7, 0x0a,
     238,  230,    7,    7, 0x0a,
     276,    7,    7,    7, 0x08,

       0        // eod
};

static const char qt_meta_stringdata_Editor[] = {
    "Editor\0\0slotRotateClockwise()\0"
    "slotRotateCounterClockwise()\0slotRemove()\0"
    "index\0slotItemMountPointChanged(int)\0"
    "slotNew()\0slotOpen()\0slotSave()\0"
    "slotSaveAs()\0slotExport()\0slotExportSdf()\0"
    "slotSelectionChanged()\0slotShowDocumentation()\0"
    "element\0slotElementTypeHovered(ArenaElement*)\0"
    "slotModified()\0"
};

void Editor::qt_static_metacall(QObject *_o, QMetaObject::Call _c, int _id, void **_a)
{
    if (_c == QMetaObject::InvokeMetaMethod) {
        Q_ASSERT(staticMetaObject.cast(_o));
        Editor *_t = static_cast<Editor *>(_o);
        switch (_id) {
        case 0: _t->slotRotateClockwise(); break;
        case 1: _t->slotRotateCounterClockwise(); break;
        case 2: _t->slotRemove(); break;
        case 3: _t->slotItemMountPointChanged((*reinterpret_cast< int(*)>(_a[1]))); break;
        case 4: _t->slotNew(); break;
        case 5: _t->slotOpen(); break;
        case 6: _t->slotSave(); break;
        case 7: _t->slotSaveAs(); break;
        case 8: _t->slotExport(); break;
        case 9: _t->slotExportSdf(); break;
        case 10: _t->slotSelectionChanged(); break;
        case 11: _t->slotShowDocumentation(); break;
        case 12: _t->slotElementTypeHovered((*reinterpret_cast< ArenaElement*(*)>(_a[1]))); break;
        case 13: _t->slotModified(); break;
        default: ;
        }
    }
}

const QMetaObjectExtraData Editor::staticMetaObjectExtraData = {
    0,  qt_static_metacall 
};

const QMetaObject Editor::staticMetaObject = {
    { &QMainWindow::staticMetaObject, qt_meta_stringdata_Editor,
      qt_meta_data_Editor, &staticMetaObjectExtraData }
};

#ifdef Q_NO_DATA_RELOCATION
const QMetaObject &Editor::getStaticMetaObject() { return staticMetaObject; }
#endif //Q_NO_DATA_RELOCATION

const QMetaObject *Editor::metaObject() const
{
    return QObject::d_ptr->metaObject ? QObject::d_ptr->metaObject : &staticMetaObject;
}

void *Editor::qt_metacast(const char *_clname)
{
    if (!_clname) return 0;
    if (!strcmp(_clname, qt_meta_stringdata_Editor))
        return static_cast<void*>(const_cast< Editor*>(this));
    return QMainWindow::qt_metacast(_clname);
}

int Editor::qt_metacall(QMetaObject::Call _c, int _id, void **_a)
{
    _id = QMainWindow::qt_metacall(_c, _id, _a);
    if (_id < 0)
        return _id;
    if (_c == QMetaObject::InvokeMetaMethod) {
        if (_id < 14)
            qt_static_metacall(this, _c, _id, _a);
        _id -= 14;
    }
    return _id;
}
QT_END_MOC_NAMESPACE
