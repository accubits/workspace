import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ChatsComponent } from './chats.component';
import { ChatRecentComponent } from './chat-recent/chat-recent.component';
import { ChatAllComponent } from './chat-all/chat-all.component';
import { ChatGroupChatComponent } from './chat-group-chat/chat-group-chat.component';
import { ChatSingleUserChatComponent } from './chat-single-user-chat/chat-single-user-chat.component';

const routes: Routes = [
  {
    path: '',
    component: ChatsComponent,
    children: [
      { path: '', redirectTo: 'recent', pathMatch: 'full' },
      { path: 'recent', component: ChatRecentComponent },
      { path: 'all', component: ChatAllComponent },
      { path: 'groupChat', component: ChatGroupChatComponent },
      { path: 'singleUser', component: ChatSingleUserChatComponent },
    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ChatsRoutingModule { }
