import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AllOrgHeaderComponent } from './all-org-header.component';

describe('AllOrgHeaderComponent', () => {
  let component: AllOrgHeaderComponent;
  let fixture: ComponentFixture<AllOrgHeaderComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AllOrgHeaderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AllOrgHeaderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
