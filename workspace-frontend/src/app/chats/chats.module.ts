import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ChatsRoutingModule } from './chats-routing.module';
import { ChatsComponent } from './chats.component';
import { ChatWrapComponent } from './chat-wrap/chat-wrap.component';
import { ChatWrapLeftComponent } from './chat-wrap-left/chat-wrap-left.component';
import { ChatWrapRightComponent } from './chat-wrap-right/chat-wrap-right.component';
import { ChatNavComponent } from './chat-nav/chat-nav.component';
import { ChatRecentComponent } from './chat-recent/chat-recent.component';
import { ChatAllComponent } from './chat-all/chat-all.component';
import { ChatGroupChatComponent } from './chat-group-chat/chat-group-chat.component';
import { ChatSingleUserChatComponent } from './chat-single-user-chat/chat-single-user-chat.component';
import { ChatMembersComponent } from './chat-members/chat-members.component';
import { ChatInviteComponent } from './chat-invite/chat-invite.component';
import { ChatAttachmentComponent } from './chat-attachment/chat-attachment.component';
import { ChatNewPopComponent } from './chat-new-pop/chat-new-pop.component';
import { ChatNewMessageComponent } from './chat-new-message/chat-new-message.component';
import { ChatNewGroupComponent } from './chat-new-group/chat-new-group.component';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';

@NgModule({
  imports: [
    CommonModule,
    ChatsRoutingModule,
    PerfectScrollbarModule
  ],
  declarations: [ChatsComponent, ChatWrapComponent, ChatWrapLeftComponent, ChatWrapRightComponent, ChatNavComponent, ChatRecentComponent, ChatAllComponent, ChatGroupChatComponent, ChatSingleUserChatComponent, ChatMembersComponent, ChatInviteComponent, ChatAttachmentComponent, ChatNewPopComponent, ChatNewMessageComponent, ChatNewGroupComponent]
})
export class ChatsModule { }
