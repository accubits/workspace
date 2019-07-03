import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserlistPreviewComponent } from './userlist-preview.component';

describe('UserlistPreviewComponent', () => {
  let component: UserlistPreviewComponent;
  let fixture: ComponentFixture<UserlistPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserlistPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserlistPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
