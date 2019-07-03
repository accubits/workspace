import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UnlicenseTableComponent } from './unlicense-table.component';

describe('UnlicenseTableComponent', () => {
  let component: UnlicenseTableComponent;
  let fixture: ComponentFixture<UnlicenseTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UnlicenseTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UnlicenseTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
