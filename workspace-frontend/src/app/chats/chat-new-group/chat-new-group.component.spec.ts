import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatNewGroupComponent } from './chat-new-group.component';

describe('ChatNewGroupComponent', () => {
  let component: ChatNewGroupComponent;
  let fixture: ComponentFixture<ChatNewGroupComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatNewGroupComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatNewGroupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
