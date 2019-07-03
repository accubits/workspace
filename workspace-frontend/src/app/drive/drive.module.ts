import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DriveRoutingModule } from './drive-routing.module';
import { DrivContainerComponent } from './driv-container/driv-container.component';
import { DriveWrapLeftComponent } from './drive-wrap-left/drive-wrap-left.component';
import { DriveWrapRightComponent } from './drive-wrap-right/drive-wrap-right.component';
import { MyDriveComponent } from './my-drive/my-drive.component';
import { CompanyDriveComponent } from './company-drive/company-drive.component';
import { ShareWithMeComponent } from './share-with-me/share-with-me.component';
import { TrashComponent } from './trash/trash.component';
import { DriveTableComponent } from './drive-table/drive-table.component';
import { CopyOptionComponent } from './copy-option/copy-option.component';
import { MoveOptionComponent } from './move-option/move-option.component';
import { ShareOptionComponent } from './share-option/share-option.component';
import { DriveSandbox } from './drive.sandbox';
import { FormsModule } from '@angular/forms';
import { SharedModule } from '../shared/shared.module';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';

@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    DriveRoutingModule,
    FormsModule,
    PerfectScrollbarModule

  ],
  declarations: [DrivContainerComponent, DriveWrapLeftComponent, DriveWrapRightComponent, MyDriveComponent, CompanyDriveComponent, ShareWithMeComponent, TrashComponent, DriveTableComponent, CopyOptionComponent, MoveOptionComponent, ShareOptionComponent],
  providers: [DriveSandbox],
})
export class DriveModule { }
