import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { KraModuleDetailComponent } from './kra-module-detail.component';

describe('KraModuleDetailComponent', () => {
  let component: KraModuleDetailComponent;
  let fixture: ComponentFixture<KraModuleDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ KraModuleDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(KraModuleDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
