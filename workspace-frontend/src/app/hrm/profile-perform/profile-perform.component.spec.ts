import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfilePerformComponent } from './profile-perform.component';

describe('ProfilePerformComponent', () => {
  let component: ProfilePerformComponent;
  let fixture: ComponentFixture<ProfilePerformComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProfilePerformComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProfilePerformComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
