import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DriveRoutingModule } from './drive-routing.module';
import { DriveContainerComponent } from '../drive_container/drive-container/drive-container.component';

@NgModule({
  imports: [
    CommonModule,
    DriveRoutingModule
  ],
  declarations: [DriveContainerComponent]
})
export class DriveModule { }
