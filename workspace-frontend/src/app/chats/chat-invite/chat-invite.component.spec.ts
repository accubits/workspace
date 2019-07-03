import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatInviteComponent } from './chat-invite.component';

describe('ChatInviteComponent', () => {
  let component: ChatInviteComponent;
  let fixture: ComponentFixture<ChatInviteComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatInviteComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatInviteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
