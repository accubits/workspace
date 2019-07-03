import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OrgPopupComponent } from './org-popup.component';

describe('OrgPopupComponent', () => {
  let component: OrgPopupComponent;
  let fixture: ComponentFixture<OrgPopupComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OrgPopupComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OrgPopupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
