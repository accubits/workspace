import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DrivContainerComponent } from './driv-container/driv-container.component';
import { MyDriveComponent } from './my-drive/my-drive.component';
import { CompanyDriveComponent } from './company-drive/company-drive.component';
import { ShareWithMeComponent } from './share-with-me/share-with-me.component';
import { TrashComponent } from './trash/trash.component';



const routes: Routes = [
  {
    path: '',
    component: DrivContainerComponent,
    children: [
      { path: '', redirectTo: 'my-drive', pathMatch: 'full' },
      { path: 'my-drive', component: MyDriveComponent },
      { path: 'company-drive', component: CompanyDriveComponent },
      { path: 'share-with-me', component: ShareWithMeComponent },
      { path: 'trash', component: TrashComponent },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DriveRoutingModule { }
