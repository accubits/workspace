import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfileLeaveComponent } from './profile-leave.component';

describe('ProfileLeaveComponent', () => {
  let component: ProfileLeaveComponent;
  let fixture: ComponentFixture<ProfileLeaveComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProfileLeaveComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProfileLeaveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
