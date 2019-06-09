/********************************************************************************
** Form generated from reading UI file 'editor.ui'
**
** Created by: Qt User Interface Compiler version 4.8.7
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_EDITOR_H
#define UI_EDITOR_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QComboBox>
#include <QtGui/QDockWidget>
#include <QtGui/QGraphicsView>
#include <QtGui/QGridLayout>
#include <QtGui/QGroupBox>
#include <QtGui/QHBoxLayout>
#include <QtGui/QHeaderView>
#include <QtGui/QLabel>
#include <QtGui/QMainWindow>
#include <QtGui/QMenu>
#include <QtGui/QMenuBar>
#include <QtGui/QSpacerItem>
#include <QtGui/QStatusBar>
#include <QtGui/QToolBar>
#include <QtGui/QWidget>
#include "../src/ui/metainfobox.h"

QT_BEGIN_NAMESPACE

class Ui_MainWindow
{
public:
    QAction *actionRotateCounterClockwise;
    QAction *actionRotateClockwise;
    QAction *actionExport;
    QAction *actionRemove;
    QAction *actionOpen;
    QAction *actionSaveAs;
    QAction *actionSave;
    QAction *actionNew;
    QAction *actionZoomIn;
    QAction *actionZoomOut;
    QAction *actionShowDocumentation;
    QAction *actionExportSdf;
    QWidget *centralWidget;
    QHBoxLayout *horizontalLayout_2;
    QGraphicsView *elementView;
    QWidget *arenaViewContainer;
    QHBoxLayout *horizontalLayout_3;
    QMenuBar *menuBar;
    QMenu *menuFile;
    QMenu *menuHelp;
    QToolBar *mainToolBar;
    QStatusBar *statusBar;
    QDockWidget *dockWidget;
    QWidget *dockWidgetContents;
    QGridLayout *gridLayout;
    QSpacerItem *verticalSpacer;
    QWidget *infoWidget;
    QGridLayout *gridLayout_3;
    MetaInfoBox *metaInfoBox;
    QGroupBox *infoBox;
    QGridLayout *gridLayout_2;
    QLabel *label_4;
    QLabel *infoPos;
    QLabel *label_2;
    QLabel *infoRot;
    QLabel *infoItemMountPointLabel;
    QComboBox *infoItemMountPoint;
    QLabel *infoOffsetLabel;
    QLabel *infoOffset;
    QLabel *infoHintLabel;

    void setupUi(QMainWindow *MainWindow)
    {
        if (MainWindow->objectName().isEmpty())
            MainWindow->setObjectName(QString::fromUtf8("MainWindow"));
        MainWindow->resize(741, 534);
        MainWindow->setMinimumSize(QSize(0, 500));
        actionRotateCounterClockwise = new QAction(MainWindow);
        actionRotateCounterClockwise->setObjectName(QString::fromUtf8("actionRotateCounterClockwise"));
        QIcon icon;
        icon.addFile(QString::fromUtf8(":/icon/icons/rotate_ccw.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionRotateCounterClockwise->setIcon(icon);
        actionRotateClockwise = new QAction(MainWindow);
        actionRotateClockwise->setObjectName(QString::fromUtf8("actionRotateClockwise"));
        QIcon icon1;
        icon1.addFile(QString::fromUtf8(":/icon/icons/rotate_cw.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionRotateClockwise->setIcon(icon1);
        actionExport = new QAction(MainWindow);
        actionExport->setObjectName(QString::fromUtf8("actionExport"));
        QIcon icon2;
        icon2.addFile(QString::fromUtf8(":/icon/icons/document_export.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionExport->setIcon(icon2);
        actionRemove = new QAction(MainWindow);
        actionRemove->setObjectName(QString::fromUtf8("actionRemove"));
        QIcon icon3;
        icon3.addFile(QString::fromUtf8(":/icon/icons/remove.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionRemove->setIcon(icon3);
        actionOpen = new QAction(MainWindow);
        actionOpen->setObjectName(QString::fromUtf8("actionOpen"));
        QIcon icon4;
        icon4.addFile(QString::fromUtf8(":/icon/icons/document_open.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionOpen->setIcon(icon4);
        actionSaveAs = new QAction(MainWindow);
        actionSaveAs->setObjectName(QString::fromUtf8("actionSaveAs"));
        QIcon icon5;
        icon5.addFile(QString::fromUtf8(":/icon/icons/document_save_as.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionSaveAs->setIcon(icon5);
        actionSave = new QAction(MainWindow);
        actionSave->setObjectName(QString::fromUtf8("actionSave"));
        QIcon icon6;
        icon6.addFile(QString::fromUtf8(":/icon/icons/document_save.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionSave->setIcon(icon6);
        actionNew = new QAction(MainWindow);
        actionNew->setObjectName(QString::fromUtf8("actionNew"));
        QIcon icon7;
        icon7.addFile(QString::fromUtf8(":/icon/icons/document_new.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionNew->setIcon(icon7);
        actionZoomIn = new QAction(MainWindow);
        actionZoomIn->setObjectName(QString::fromUtf8("actionZoomIn"));
        QIcon icon8;
        icon8.addFile(QString::fromUtf8(":/icon/icons/zoom_in.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionZoomIn->setIcon(icon8);
        actionZoomOut = new QAction(MainWindow);
        actionZoomOut->setObjectName(QString::fromUtf8("actionZoomOut"));
        QIcon icon9;
        icon9.addFile(QString::fromUtf8(":/icon/icons/zoom_out.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionZoomOut->setIcon(icon9);
        actionShowDocumentation = new QAction(MainWindow);
        actionShowDocumentation->setObjectName(QString::fromUtf8("actionShowDocumentation"));
        QIcon icon10;
        icon10.addFile(QString::fromUtf8(":/icon/icons/help_contents.png"), QSize(), QIcon::Normal, QIcon::Off);
        actionShowDocumentation->setIcon(icon10);
        actionExportSdf = new QAction(MainWindow);
        actionExportSdf->setObjectName(QString::fromUtf8("actionExportSdf"));
        centralWidget = new QWidget(MainWindow);
        centralWidget->setObjectName(QString::fromUtf8("centralWidget"));
        horizontalLayout_2 = new QHBoxLayout(centralWidget);
        horizontalLayout_2->setSpacing(6);
        horizontalLayout_2->setContentsMargins(11, 11, 11, 11);
        horizontalLayout_2->setObjectName(QString::fromUtf8("horizontalLayout_2"));
        elementView = new QGraphicsView(centralWidget);
        elementView->setObjectName(QString::fromUtf8("elementView"));
        QSizePolicy sizePolicy(QSizePolicy::Fixed, QSizePolicy::Expanding);
        sizePolicy.setHorizontalStretch(0);
        sizePolicy.setVerticalStretch(0);
        sizePolicy.setHeightForWidth(elementView->sizePolicy().hasHeightForWidth());
        elementView->setSizePolicy(sizePolicy);
        elementView->setMinimumSize(QSize(100, 0));
        elementView->setMaximumSize(QSize(200, 16777215));

        horizontalLayout_2->addWidget(elementView);

        arenaViewContainer = new QWidget(centralWidget);
        arenaViewContainer->setObjectName(QString::fromUtf8("arenaViewContainer"));
        horizontalLayout_3 = new QHBoxLayout(arenaViewContainer);
        horizontalLayout_3->setSpacing(6);
        horizontalLayout_3->setContentsMargins(0, 0, 0, 0);
        horizontalLayout_3->setObjectName(QString::fromUtf8("horizontalLayout_3"));

        horizontalLayout_2->addWidget(arenaViewContainer);

        MainWindow->setCentralWidget(centralWidget);
        menuBar = new QMenuBar(MainWindow);
        menuBar->setObjectName(QString::fromUtf8("menuBar"));
        menuBar->setGeometry(QRect(0, 0, 741, 25));
        menuFile = new QMenu(menuBar);
        menuFile->setObjectName(QString::fromUtf8("menuFile"));
        menuHelp = new QMenu(menuBar);
        menuHelp->setObjectName(QString::fromUtf8("menuHelp"));
        MainWindow->setMenuBar(menuBar);
        mainToolBar = new QToolBar(MainWindow);
        mainToolBar->setObjectName(QString::fromUtf8("mainToolBar"));
        MainWindow->addToolBar(Qt::TopToolBarArea, mainToolBar);
        statusBar = new QStatusBar(MainWindow);
        statusBar->setObjectName(QString::fromUtf8("statusBar"));
        MainWindow->setStatusBar(statusBar);
        dockWidget = new QDockWidget(MainWindow);
        dockWidget->setObjectName(QString::fromUtf8("dockWidget"));
        dockWidget->setMinimumSize(QSize(200, 339));
        dockWidget->setMaximumSize(QSize(270, 524287));
        dockWidgetContents = new QWidget();
        dockWidgetContents->setObjectName(QString::fromUtf8("dockWidgetContents"));
        gridLayout = new QGridLayout(dockWidgetContents);
        gridLayout->setSpacing(6);
        gridLayout->setContentsMargins(11, 11, 11, 11);
        gridLayout->setObjectName(QString::fromUtf8("gridLayout"));
        verticalSpacer = new QSpacerItem(20, 40, QSizePolicy::Minimum, QSizePolicy::Expanding);

        gridLayout->addItem(verticalSpacer, 4, 0, 1, 2);

        infoWidget = new QWidget(dockWidgetContents);
        infoWidget->setObjectName(QString::fromUtf8("infoWidget"));
        gridLayout_3 = new QGridLayout(infoWidget);
        gridLayout_3->setSpacing(6);
        gridLayout_3->setContentsMargins(0, 0, 0, 0);
        gridLayout_3->setObjectName(QString::fromUtf8("gridLayout_3"));
        gridLayout_3->setHorizontalSpacing(6);
        metaInfoBox = new MetaInfoBox(infoWidget);
        metaInfoBox->setObjectName(QString::fromUtf8("metaInfoBox"));

        gridLayout_3->addWidget(metaInfoBox, 1, 0, 1, 1);

        infoBox = new QGroupBox(infoWidget);
        infoBox->setObjectName(QString::fromUtf8("infoBox"));
        infoBox->setMinimumSize(QSize(170, 0));
        gridLayout_2 = new QGridLayout(infoBox);
        gridLayout_2->setSpacing(6);
        gridLayout_2->setContentsMargins(11, 11, 11, 11);
        gridLayout_2->setObjectName(QString::fromUtf8("gridLayout_2"));
        label_4 = new QLabel(infoBox);
        label_4->setObjectName(QString::fromUtf8("label_4"));

        gridLayout_2->addWidget(label_4, 1, 0, 1, 1);

        infoPos = new QLabel(infoBox);
        infoPos->setObjectName(QString::fromUtf8("infoPos"));

        gridLayout_2->addWidget(infoPos, 1, 1, 1, 1);

        label_2 = new QLabel(infoBox);
        label_2->setObjectName(QString::fromUtf8("label_2"));

        gridLayout_2->addWidget(label_2, 2, 0, 1, 1);

        infoRot = new QLabel(infoBox);
        infoRot->setObjectName(QString::fromUtf8("infoRot"));

        gridLayout_2->addWidget(infoRot, 2, 1, 1, 1);

        infoItemMountPointLabel = new QLabel(infoBox);
        infoItemMountPointLabel->setObjectName(QString::fromUtf8("infoItemMountPointLabel"));

        gridLayout_2->addWidget(infoItemMountPointLabel, 3, 0, 1, 1);

        infoItemMountPoint = new QComboBox(infoBox);
        infoItemMountPoint->setObjectName(QString::fromUtf8("infoItemMountPoint"));

        gridLayout_2->addWidget(infoItemMountPoint, 3, 1, 1, 1);

        infoOffsetLabel = new QLabel(infoBox);
        infoOffsetLabel->setObjectName(QString::fromUtf8("infoOffsetLabel"));

        gridLayout_2->addWidget(infoOffsetLabel, 4, 0, 1, 1);

        infoOffset = new QLabel(infoBox);
        infoOffset->setObjectName(QString::fromUtf8("infoOffset"));

        gridLayout_2->addWidget(infoOffset, 4, 1, 1, 1);


        gridLayout_3->addWidget(infoBox, 0, 0, 1, 1);


        gridLayout->addWidget(infoWidget, 0, 0, 3, 2);

        infoHintLabel = new QLabel(dockWidgetContents);
        infoHintLabel->setObjectName(QString::fromUtf8("infoHintLabel"));
        infoHintLabel->setWordWrap(true);

        gridLayout->addWidget(infoHintLabel, 3, 0, 1, 2);

        dockWidget->setWidget(dockWidgetContents);
        MainWindow->addDockWidget(static_cast<Qt::DockWidgetArea>(2), dockWidget);

        menuBar->addAction(menuFile->menuAction());
        menuBar->addAction(menuHelp->menuAction());
        menuFile->addAction(actionNew);
        menuFile->addAction(actionOpen);
        menuFile->addAction(actionSave);
        menuFile->addAction(actionSaveAs);
        menuFile->addAction(actionExport);
        menuFile->addAction(actionExportSdf);
        menuHelp->addAction(actionShowDocumentation);
        mainToolBar->addAction(actionRotateCounterClockwise);
        mainToolBar->addAction(actionRotateClockwise);
        mainToolBar->addAction(actionRemove);
        mainToolBar->addSeparator();
        mainToolBar->addAction(actionZoomIn);
        mainToolBar->addAction(actionZoomOut);

        retranslateUi(MainWindow);

        QMetaObject::connectSlotsByName(MainWindow);
    } // setupUi

    void retranslateUi(QMainWindow *MainWindow)
    {
        MainWindow->setWindowTitle(QApplication::translate("MainWindow", "RoboCup Rescue Arena Designer", 0, QApplication::UnicodeUTF8));
        actionRotateCounterClockwise->setText(QApplication::translate("MainWindow", "Rotate counter-clockwise", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionRotateCounterClockwise->setToolTip(QApplication::translate("MainWindow", "Rotate element counter-clockwise by 90\302\260", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionRotateCounterClockwise->setShortcut(QApplication::translate("MainWindow", "L", 0, QApplication::UnicodeUTF8));
        actionRotateClockwise->setText(QApplication::translate("MainWindow", "Rotate clockwise", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionRotateClockwise->setToolTip(QApplication::translate("MainWindow", "Rotate element clockwise by 90\302\260", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionRotateClockwise->setShortcut(QApplication::translate("MainWindow", "R", 0, QApplication::UnicodeUTF8));
        actionExport->setText(QApplication::translate("MainWindow", "Export to .world File ...", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionExport->setToolTip(QApplication::translate("MainWindow", "Export arena to a format readable by Gazebo", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionRemove->setText(QApplication::translate("MainWindow", "Remove", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionRemove->setToolTip(QApplication::translate("MainWindow", "Remove element from arena", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionRemove->setShortcut(QApplication::translate("MainWindow", "Del", 0, QApplication::UnicodeUTF8));
        actionOpen->setText(QApplication::translate("MainWindow", "Open Arena ...", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionOpen->setToolTip(QApplication::translate("MainWindow", "Open an arena .raf file ...", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionOpen->setShortcut(QApplication::translate("MainWindow", "Ctrl+O", 0, QApplication::UnicodeUTF8));
        actionSaveAs->setText(QApplication::translate("MainWindow", "Save Arena as...", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionSaveAs->setToolTip(QApplication::translate("MainWindow", "Save arena as .raf file ...", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionSaveAs->setShortcut(QApplication::translate("MainWindow", "Ctrl+Shift+S", 0, QApplication::UnicodeUTF8));
        actionSave->setText(QApplication::translate("MainWindow", "Save Arena", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionSave->setToolTip(QApplication::translate("MainWindow", "Saves the current arena", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionSave->setShortcut(QApplication::translate("MainWindow", "Ctrl+S", 0, QApplication::UnicodeUTF8));
        actionNew->setText(QApplication::translate("MainWindow", "New arena", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionNew->setToolTip(QApplication::translate("MainWindow", "Opens a new, empty arena", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionZoomIn->setText(QApplication::translate("MainWindow", "Zoom In", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionZoomIn->setToolTip(QApplication::translate("MainWindow", "Magnify the arena", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionZoomOut->setText(QApplication::translate("MainWindow", "Zoom Out", 0, QApplication::UnicodeUTF8));
        actionShowDocumentation->setText(QApplication::translate("MainWindow", "Open HTML Documentation", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        actionShowDocumentation->setToolTip(QApplication::translate("MainWindow", "Open the Designer's HTML documentation in a webbrowser", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        actionExportSdf->setText(QApplication::translate("MainWindow", "Export to .sdf File ...", 0, QApplication::UnicodeUTF8));
        actionExportSdf->setShortcut(QApplication::translate("MainWindow", "Ctrl+E", 0, QApplication::UnicodeUTF8));
        menuFile->setTitle(QApplication::translate("MainWindow", "File", 0, QApplication::UnicodeUTF8));
        menuHelp->setTitle(QApplication::translate("MainWindow", "Help", 0, QApplication::UnicodeUTF8));
        metaInfoBox->setTitle(QApplication::translate("MainWindow", "Component Info", 0, QApplication::UnicodeUTF8));
        infoBox->setTitle(QApplication::translate("MainWindow", "Selection Info", 0, QApplication::UnicodeUTF8));
        label_4->setText(QApplication::translate("MainWindow", "<b>Position</b>", 0, QApplication::UnicodeUTF8));
        infoPos->setText(QString());
        label_2->setText(QApplication::translate("MainWindow", "<b>Rotation</b>", 0, QApplication::UnicodeUTF8));
        infoRot->setText(QString());
        infoItemMountPointLabel->setText(QApplication::translate("MainWindow", "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\" \"http://www.w3.org/TR/REC-html40/strict.dtd\">\n"
"<html><head><meta name=\"qrichtext\" content=\"1\" /><style type=\"text/css\">\n"
"p, li { white-space: pre-wrap; }\n"
"</style></head><body style=\" font-family:'Lucida Grande'; font-size:13pt; font-weight:400; font-style:normal;\">\n"
"<p style=\" margin-top:0px; margin-bottom:0px; margin-left:0px; margin-right:0px; -qt-block-indent:0; text-indent:0px;\"><span style=\" font-weight:600;\">Mount Point</span></p></body></html>", 0, QApplication::UnicodeUTF8));
        infoOffsetLabel->setText(QApplication::translate("MainWindow", "<b>Offset</b>", 0, QApplication::UnicodeUTF8));
        infoOffset->setText(QString());
        infoHintLabel->setText(QApplication::translate("MainWindow", "<b>Help</b><br/>\n"
"Select an element in the arena to display its properties.<br/>\n"
"<br/>\n"
"To allow custom-shaped elements, transparent parts of element icons can not be selected.<br/>\n"
"<br/>\n"
"If you want to select multiple objects, either use the rubber-band selection tool (left-click and drag) or press and hold Ctrl (Cmd on OS X).", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class MainWindow: public Ui_MainWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_EDITOR_H
